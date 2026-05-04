<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'metode_bayar' => 'required|in:1,2',
        ]);
        
        // Validasi nama hanya untuk guest
        if (!Auth::check()) {
            $request->validate([
                'nama' => 'required|string|max:255',
            ]);
        }
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }
        
        DB::beginTransaction();
        
        try {
            // Tentukan user_id (bisa null untuk guest)
            $userId = Auth::check() ? Auth::id() : null;
            
            // Tentukan nama pemesan
            if (Auth::check()) {
                $namaPemesan = Auth::user()->name;
            } else {
                $namaPemesan = $request->nama;
            }
            
            $pesanan = Pesanan::create([
                'nama' => $namaPemesan,
                'total' => $total,
                'metode_bayar' => $request->metode_bayar,
                'status_bayar' => Pesanan::STATUS_PENDING,
                'timestamp' => now(),
                'user_id' => $userId,
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
            
            return redirect()->route('payment.show', $pesanan->idpesanan)->with('success', 'Pesanan berhasil dibuat');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}