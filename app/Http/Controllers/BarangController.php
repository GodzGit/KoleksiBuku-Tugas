<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarangController extends Controller
{
    public function index()
    {
        $data = Barang::all();
        return view('barang.index', compact('data'));
    }

    public function show($id)
    {
        dd("MASUK SHOW", $id);
    }

    // 🔥 FORM CREATE
    public function create()
    {
        return view('barang.create');
    }

    // 🔥 SIMPAN
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'harga' => 'required|integer',
            'stok' => 'required|integer'
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')->with('success','Berhasil tambah barang');
    }

    // 🔥 FORM EDIT
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    // 🔥 UPDATE
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'harga' => 'required|integer',
            'stok' => 'required|integer'
        ]);

        $barang->update($request->all());

        return redirect()->route('barang.index')->with('success','Berhasil update');
    }

    // 🔥 DELETE
    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();
        return back()->with('success','Berhasil hapus');
    }

    // 🔥 CETAK
    public function cetak(Request $request)
    {
        // dd("masuk cetak" , $request->all());
        $request->validate([
            'selected' => 'required|array|min:1',
            'x' => 'required|integer|min:1|max:5',
            'y' => 'required|integer|min:1|max:8',
        ]);

        $barang = Barang::whereIn('id_barang', $request->selected)->get();

        $generator = new BarcodeGeneratorPNG();

        foreach ($barang as $item) {
            $barcode = $generator->getBarcode(
                $item->kode_barang, // 🔥 GANTI INI
                $generator::TYPE_CODE_128,
                2,   // biar tebal
                60   // biar tinggi
            );

            $item->barcode = base64_encode($barcode);
        }

        $index_awal = ($request->y - 1) * 5 + $request->x;

        $pdf = Pdf::loadView('pdf.label', compact('barang','index_awal'))
                ->setPaper('a4','portrait');

        return $pdf->stream('label.pdf');
    }
}