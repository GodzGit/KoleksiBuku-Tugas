<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function index()
    {
        $data = Barang::all();
        return view('barang.index', compact('data'));
    }

    public function store(Request $request)
    {
        Barang::create($request->all());
        return redirect()->back();
    }

    public function cetak(Request $request)
    {
        $selected = $request->selected;
        $x = $request->x;
        $y = $request->y;

        $barang = barang::whereIn('id_barang', $selected)->get();
        $index_awal = ($y - 1) * 5 + $x;

        $pdf = Pdf::loadView('pdf.label', compact('barang','index_awal'))
                ->setPaper('a4','portrait');

        return $pdf->stream('label.pdf');
    }
}
