<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
    
    public function show($idpesanan)
    {
        $pesanan = Pesanan::with('detailPesanan.menu')->findOrFail($idpesanan);
        
        // Jika sudah lunas, redirect ke halaman sukses
        if ($pesanan->isLunas()) {
            return redirect()->route('payment.success', $pesanan->idpesanan);
        }
        
        // Jika sudah batal, redirect ke landing dengan pesan error
        if ($pesanan->status_bayar == Pesanan::STATUS_BATAL) {
            return redirect()->route('landing')->with('error', 'Pesanan telah dibatalkan.');
        }
        
        return view('payment.show', compact('pesanan'));
    }
    
    public function process(Request $request, $idpesanan)
    {
        $pesanan = Pesanan::with('detailPesanan.menu')->findOrFail($idpesanan);
        
        // Cek apakah pesanan sudah lunas
        if ($pesanan->isLunas()) {
            return response()->json(['error' => 'Pesanan sudah lunas'], 400);
        }
        
        // Prepare item details
        $itemDetails = [];
        foreach ($pesanan->detailPesanan as $detail) {
            $itemDetails[] = [
                'id' => $detail->idmenu,
                'price' => (int) $detail->harga,
                'quantity' => (int) $detail->jumlah,
                'name' => $detail->menu->nama_menu
            ];
        }
        
        // Buat email valid
        $cleanName = preg_replace('/[^a-zA-Z0-9]/', '', $pesanan->nama);
        $email = $cleanName . '@customer.com';
        
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $pesanan->idpesanan . '-' . time(),
                'gross_amount' => (int) $pesanan->total,
            ],
            'customer_details' => [
                'first_name' => $pesanan->nama ?: 'Customer',
                'email' => $email,
                'phone' => '08123456789',
            ],
            'item_details' => $itemDetails,
        ];
        
        // Set payment method berdasarkan pilihan di checkout
        if ($pesanan->metode_bayar == Pesanan::METODE_VA) {
            $params['payment_method'] = 'bank_transfer';
            $params['bank_transfer'] = ['bank' => 'bca'];
        } elseif ($pesanan->metode_bayar == Pesanan::METODE_QRIS) {
            $params['payment_method'] = 'qris';
        }
        
        try {
            $snapToken = Snap::getSnapToken($params);
            
            // Simpan snap token ke session
            session()->put('snap_token_' . $pesanan->idpesanan, $snapToken);
            
            return response()->json(['token' => $snapToken]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function notification(Request $request)
    {
        $notification = new Notification();
        
        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        
        // Extract pesanan ID from order_id (ORDER-123-1234567890)
        preg_match('/ORDER-(\d+)-/', $orderId, $matches);
        $idpesanan = $matches[1] ?? null;
        
        if (!$idpesanan) {
            return response()->json(['error' => 'Invalid order ID'], 400);
        }
        
        $pesanan = Pesanan::find($idpesanan);
        
        if (!$pesanan) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        
        // Update status berdasarkan notifikasi
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $pesanan->update([
                'status_bayar' => Pesanan::STATUS_LUNAS
            ]);
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $pesanan->update([
                'status_bayar' => Pesanan::STATUS_BATAL
            ]);
        }
        
        return response()->json(['status' => 'ok']);
    }
    
    public function success($idpesanan)
    {
        $pesanan = Pesanan::with('detailPesanan.menu')->findOrFail($idpesanan);
        
        // Cek ulang status pesanan dari database
        // Refresh dari database untuk memastikan data terbaru
        $pesanan->refresh();
        
        if (!$pesanan->isLunas()) {
            // Jika belum lunas, tunggu notifikasi atau cek manual
            return redirect()->route('payment.show', $pesanan->idpesanan)
                ->with('warning', 'Pembayaran sedang diproses. Silakan tunggu atau refresh halaman ini.');
        }
        
        return view('payment.success', compact('pesanan'));
    }
    
    // Method untuk cek status pesanan via AJAX
    public function checkStatus($idpesanan)
    {
        $pesanan = Pesanan::find($idpesanan);
        
        if (!$pesanan) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        
        return response()->json([
            'status' => $pesanan->status_bayar,
            'is_lunas' => $pesanan->isLunas(),
            'status_text' => $pesanan->status_text
        ]);
    }

    public function updateStatus(Request $request, $idpesanan)
    {
        $pesanan = Pesanan::findOrFail($idpesanan);
        
        if ($request->status === 'success') {
            $pesanan->update([
                'status_bayar' => Pesanan::STATUS_LUNAS
            ]);
            
            return response()->json(['success' => true, 'message' => 'Status updated']);
        }
        
        return response()->json(['success' => false, 'message' => 'Invalid status']);
    }
}