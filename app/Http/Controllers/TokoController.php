<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Auth;

class TokoController extends Controller
{
    // Halaman utama
    public function index()
    {
        $tokos = Toko::all();
        return view('toko.index', compact('tokos'));
    }
    
    // Form tambah toko
    public function create()
    {
        return view('toko.create');
    }
    
    // Simpan toko
    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|unique:toko',
            'nama_toko' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'required|integer'
        ]);
        
        Toko::create($request->all());
        
        return redirect()->route('toko.index')->with('success', 'Toko berhasil ditambahkan');
    }
    
    // Edit toko
    public function edit($id)
    {
        $toko = Toko::findOrFail($id);
        return view('toko.edit', compact('toko'));
    }
    
    // Update toko
    public function update(Request $request, $id)
    {
        $toko = Toko::findOrFail($id);
        
        $request->validate([
            'barcode' => 'required|unique:toko,barcode,' . $id . ',id_toko',
            'nama_toko' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'required|integer'
        ]);
        
        $toko->update($request->all());
        
        return redirect()->route('toko.index')->with('success', 'Toko berhasil diupdate');
    }
    
    // Hapus toko
    public function destroy($id)
    {
        Toko::findOrFail($id)->delete();
        return redirect()->route('toko.index')->with('success', 'Toko berhasil dihapus');
    }
    
    // Cetak barcode
    public function cetakBarcode($id)
    {
        $toko = Toko::findOrFail($id);
        
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode(
            $toko->barcode,
            $generator::TYPE_CODE_128,
            2,
            60
        );
        
        $barcodeBase64 = base64_encode($barcode);
        
        return view('toko.barcode', compact('toko', 'barcodeBase64'));
    }
    
    // Halaman kunjungan
    public function kunjungan()
    {
        return view('toko.kunjungan');
    }
    
    // API: cek toko dari barcode
    public function cekToko($barcode)
    {
        $toko = Toko::where('barcode', $barcode)->first();
        
        if ($toko) {
            return response()->json([
                'success' => true,
                'toko' => [
                    'id_toko' => $toko->id_toko,
                    'nama_toko' => $toko->nama_toko,
                    'alamat' => $toko->alamat,
                    'latitude' => $toko->latitude,
                    'longitude' => $toko->longitude,
                    'accuracy' => $toko->accuracy
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Barcode tidak ditemukan'
        ]);
    }
    
    // API: proses validasi kunjungan
    public function prosesKunjungan(Request $request)
    {
        $request->validate([
            'id_toko' => 'required|exists:toko,id_toko',
            'nama_sales' => 'required|string|max:255',  // ← manual input
            'latitude_sales' => 'required|numeric',
            'longitude_sales' => 'required|numeric',
            'accuracy_sales' => 'required|numeric'
        ]);
        
        $toko = Toko::findOrFail($request->id_toko);
        
        // Hitung jarak dengan Haversine
        $jarak = $this->haversine(
            $toko->latitude, $toko->longitude,
            $request->latitude_sales, $request->longitude_sales
        );
        
        // Threshold (tentukan sendiri, misal 300 meter)
        $threshold = 300;
        
        // Efektif = threshold + accuracy toko + accuracy sales
        $thresholdEfektif = $threshold + $toko->accuracy + $request->accuracy_sales;
        
        $status = $jarak <= $thresholdEfektif ? 'diterima' : 'ditolak';
        
        // Simpan ke history
        $kunjungan = Kunjungan::create([
            'id_toko' => $toko->id_toko,
            'nama_sales' => $request->nama_sales ?? 'Sales',
            'latitude_sales' => $request->latitude_sales,
            'longitude_sales' => $request->longitude_sales,
            'accuracy_sales' => $request->accuracy_sales,
            'jarak_hitung' => $jarak,
            'status' => $status
        ]);
        
        return response()->json([
            'success' => true,
            'status' => $status,
            'jarak' => round($jarak, 2),
            'threshold' => $threshold,
            'accuracy_toko' => $toko->accuracy,
            'accuracy_sales' => $request->accuracy_sales,
            'threshold_efektif' => $thresholdEfektif,
            'nama_toko' => $toko->nama_toko,
            'id_kunjungan' => $kunjungan->id_kunjungan
        ]);
    }
    
    // Haversine Formula (jarak dalam meter)
    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }
    
    // History kunjungan
    public function history()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Admin melihat SEMUA kunjungan
            $kunjungans = Kunjungan::with('toko')->orderBy('created_at', 'desc')->get();
        } else {
            // Sales hanya melihat kunjungannya sendiri
            $kunjungans = Kunjungan::with('toko')
                ->where('nama_sales', $user->name)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('toko.history', compact('kunjungans'));
    }
}