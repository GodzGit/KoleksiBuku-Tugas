<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;

class DashboardController extends Controller
{
    public function index()
{
    $totalBuku = Buku::count();
    $totalKategori = Kategori::count();

    return view('dashboard', compact('totalBuku', 'totalKategori'));
}
}
