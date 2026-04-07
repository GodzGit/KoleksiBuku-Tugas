<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Vendor;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:vendor');  // ← format: role:vendor
    }

    // Ambil vendor dari user yang login
    private function getVendor()
    {
        return Auth::user()->vendor;
    }

    public function dashboard()
    {
        $vendor = $this->getVendor();
        
        $totalMenu = Menu::where('idvendor', $vendor->idvendor)->count();
        $totalPesananLunas = Pesanan::whereHas('detailPesanan.menu', function($q) use ($vendor) {
            $q->where('idvendor', $vendor->idvendor);
        })->where('status_bayar', Pesanan::STATUS_LUNAS)->count();
        
        $pendapatan = DetailPesanan::whereHas('menu', function($q) use ($vendor) {
            $q->where('idvendor', $vendor->idvendor);
        })->whereHas('pesanan', function($q) {
            $q->where('status_bayar', Pesanan::STATUS_LUNAS);
        })->sum('subtotal');
        
        return view('vendor.dashboard', compact('vendor', 'totalMenu', 'totalPesananLunas', 'pendapatan'));
    }
    
    public function menuIndex()
    {
        $vendor = $this->getVendor();
        $menus = Menu::where('idvendor', $vendor->idvendor)->get();
        return view('vendor.menu.index', compact('menus'));
    }
    
    public function menuCreate()
    {
        return view('vendor.menu.create');
    }
    
    public function menuStore(Request $request)
    {
        $vendor = $this->getVendor();
        
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
        ]);
        
        $pathGambar = null;
        
        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $request->nama_menu) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/menu'), $filename);
            $pathGambar = 'images/menu/' . $filename;
        }
        
        Menu::create([
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
            'path_gambar' => $pathGambar,
            'idvendor' => $vendor->idvendor
        ]);
        
        return redirect()->route('vendor.menu.index')->with('success', 'Menu berhasil ditambahkan');
    }
    
    public function menuEdit($idmenu)
    {
        $vendor = $this->getVendor();
        $menu = Menu::where('idvendor', $vendor->idvendor)->where('idmenu', $idmenu)->firstOrFail();
        return view('vendor.menu.edit', compact('menu'));
    }
    
    public function menuUpdate(Request $request, $idmenu)
    {
        $vendor = $this->getVendor();
        $menu = Menu::where('idvendor', $vendor->idvendor)->where('idmenu', $idmenu)->firstOrFail();
        
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $pathGambar = $menu->path_gambar;
        
        // Upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($menu->path_gambar && file_exists(public_path($menu->path_gambar))) {
                unlink(public_path($menu->path_gambar));
            }
            
            $file = $request->file('gambar');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $request->nama_menu) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/menu'), $filename);
            $pathGambar = 'images/menu/' . $filename;
        }
        
        $menu->update([
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
            'path_gambar' => $pathGambar,
        ]);
        
        return redirect()->route('vendor.menu.index')->with('success', 'Menu berhasil diupdate');
    }
    
    public function menuDestroy($idmenu)
    {
        $vendor = $this->getVendor();
        $menu = Menu::where('idvendor', $vendor->idvendor)->where('idmenu', $idmenu)->firstOrFail();
        
        // Hapus file gambar jika ada
        if ($menu->path_gambar && file_exists(public_path($menu->path_gambar))) {
            unlink(public_path($menu->path_gambar));
        }
        
        $menu->delete();
        
        return redirect()->route('vendor.menu.index')->with('success', 'Menu berhasil dihapus');
    }
    
    public function ordersIndex()
    {
        $vendor = $this->getVendor();
        
        $orders = Pesanan::whereHas('detailPesanan.menu', function($q) use ($vendor) {
            $q->where('idvendor', $vendor->idvendor);
        })
        ->where('status_bayar', Pesanan::STATUS_LUNAS)
        ->with('detailPesanan.menu')
        ->orderBy('timestamp', 'desc')
        ->get();
        
        return view('vendor.orders.index', compact('orders'));
    }
    
    public function ordersShow($idpesanan)
    {
        $pesanan = Pesanan::with('detailPesanan.menu')->findOrFail($idpesanan);
        return view('vendor.orders.show', compact('pesanan'));
    }
}