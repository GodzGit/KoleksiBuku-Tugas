<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Vendor;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $query = Pesanan::with(['detailPesanan.menu.vendor']);

        if ($request->filled('vendor')) {
            $query->whereHas('detailPesanan.menu.vendor', function($q) use ($request) {
                $q->where('idvendor', $request->vendor);
            });
        }

        if ($request->filled('status')) {
            $query->where('status_bayar', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('timestamp', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('timestamp', '<=', $request->end_date);
        }

        $transactions = $query->orderBy('timestamp', 'desc')->paginate(15);
        $vendors = Vendor::all();

        return view('admin.transactions.index', compact('transactions', 'vendors'));
    }

    public function show($idpesanan)
    {
        $transaction = Pesanan::with(['detailPesanan.menu.vendor'])->findOrFail($idpesanan);
        return view('admin.transactions.show', compact('transaction'));
    }
}