<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;


class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get();
        return view('koleksi-buku.index', compact('buku'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('koleksi-buku.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idkategori' => 'required',
            'kode' => 'required',
            'judul' => 'required',
            'pengarang' => 'required'
        ]);

        Buku::create($request->all());

        return redirect()->route('koleksi-buku.index')
                         ->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategori = Kategori::all();
        return view('koleksi-buku.edit', compact('buku','kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'idkategori' => 'required',
            'kode' => 'required',
            'judul' => 'required',
            'pengarang' => 'required'
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());

        return redirect()->route('koleksi-buku.index')
                         ->with('success', 'Buku berhasil diupdate');
    }

    public function destroy($id)
    {
        Buku::findOrFail($id)->delete();

        return redirect()->route('koleksi-buku.index')
                         ->with('success', 'Buku berhasil dihapus');
    }
}
