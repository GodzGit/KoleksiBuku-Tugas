<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('landing')->with('error', 'Keranjang kosong!');
        }
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }
        
        return view('checkout.index', compact('cart', 'total'));
    }
    
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('landing')->with('error', 'Keranjang kosong!');
        }
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'metode_bayar' => 'required|in:1,2',
        ]);
        
        // Jika nama dimulai dengan Guest_, generate otomatis
        $nama = $request->nama;
        if (str_starts_with($nama, 'Guest_')) {
            $nama = Pesanan::generateGuestName();
        }
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }
        
        DB::beginTransaction();
        
        try {
            // Create pesanan dengan metode_bayar yang dipilih
            $pesanan = Pesanan::create([
                'nama' => $nama,
                'total' => $total,
                'metode_bayar' => 1, // ← INI SUDAH DISIMPAN
                'status_bayar' => Pesanan::STATUS_PENDING,
                'timestamp' => now()
            ]);
            
            // Create detail pesanan
            foreach ($cart as $item) {
                DetailPesanan::create([
                    'idmenu' => $item['idmenu'],
                    'idpesanan' => $pesanan->idpesanan,
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['harga'] * $item['jumlah'],
                    'catatan' => $request->catatan[$item['idmenu']] ?? null
                ]);
            }
            
            // Clear cart
            session()->forget('cart');
            
            DB::commit();
            
            // Redirect langsung ke payment dengan metode yang sudah dipilih
            return redirect()->route('payment.show', $pesanan->idpesanan);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}