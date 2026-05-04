<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;

class VendorScanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:vendor');
    }

    public function index()
    {
        return view('vendor.scan');
    }

    public function cekPesanan($idpesanan)
    {
        $pesanan = Pesanan::with(['detailPesanan.menu', 'user'])
            ->where('idpesanan', $idpesanan)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ]);
        }

        // Cek apakah pesanan ini milik vendor yang login
        $vendorId = Auth::user()->vendor->idvendor ?? 0;
        $isMilikVendor = $pesanan->detailPesanan->contains(function($detail) use ($vendorId) {
            return $detail->menu->idvendor == $vendorId;
        });

        if (!$isMilikVendor) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini bukan untuk vendor Anda'
            ]);
        }

        // Ambil hanya menu dari vendor ini
        $items = $pesanan->detailPesanan->filter(function($detail) use ($vendorId) {
            return $detail->menu->idvendor == $vendorId;
        })->map(function($detail) {
            return [
                'nama_menu' => $detail->menu->nama_menu,
                'jumlah' => $detail->jumlah,
                'harga' => $detail->harga,
                'subtotal' => $detail->subtotal,
                'catatan' => $detail->catatan
            ];
        });

        return response()->json([
            'success' => true,
            'pesanan' => [
                'idpesanan' => $pesanan->idpesanan,
                'nama_customer' => $pesanan->nama,
                'total' => $pesanan->total,
                'status_bayar' => $pesanan->status_bayar,
                'status_text' => $pesanan->status_text,
                'status_badge' => $pesanan->status_badge,
                'items' => $items
            ]
        ]);
    }
}