<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index() {
        return view('transaksi.index');
    }

    public function cekBarang($kode) {
        $barang = Barang::where('kode_barang', $kode)->first();
        if ($barang) {
            return response()->json(['success' => true, 'data' => $barang]);
        }
        return response()->json(['success' => false]);
    }

    public function simpan(Request $request) {
        if (!$request->has('items') || count($request->items) == 0) {
            return response()->json(['success' => false, 'msg' => 'Keranjang kosong']);
        }

        DB::beginTransaction();
        try {
            // 1. SIMPAN KE TABEL PENJUALAN (Header)
            $penjualan = new \App\Models\Penjualan(); // Pakai nama class lengkap agar aman
            $penjualan->total = $request->total;
            $penjualan->timestamp = now();
            $penjualan->save(); 

            // 2. SIMPAN KE TABEL PENJUALAN_DETAIL
            foreach ($request->items as $item) {
                $detail = new \App\Models\PenjualanDetail(); // Pastikan menunjuk ke model Detail
                $detail->id_penjualan = $penjualan->id_penjualan; // ID dari hasil save di atas
                $detail->id_barang    = $item['id_barang'];
                $detail->jumlah       = $item['qty'];
                $detail->subtotal     = $item['subtotal'];
                $detail->save();

                // Kurangi stok barang
                \App\Models\Barang::where('id_barang', $item['id_barang'])->decrement('stok', $item['qty']);
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false, 
                'msg' => 'Gagal menyimpan: ' . $e->getMessage()
            ]);
        }
    }
}