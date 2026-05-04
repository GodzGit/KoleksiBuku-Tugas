<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:customer');
    }

    public function index()
    {
        $pesanans = Pesanan::where('user_id', Auth::id())
            ->orderBy('timestamp', 'desc')
            ->get();
        
        return view('history.index', compact('pesanans'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::where('user_id', Auth::id())
            ->where('idpesanan', $id)
            ->with('detailPesanan.menu')
            ->firstOrFail();
        
        return view('history.detail', compact('pesanan'));
    }
}