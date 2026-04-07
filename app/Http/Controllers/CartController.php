<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }
        
        return view('cart.index', compact('cart', 'total'));
    }
    
    public function add(Request $request, $idmenu)
    {
        $menu = Menu::with('vendor')->findOrFail($idmenu);
        $cart = session()->get('cart', []);
        
        if (isset($cart[$idmenu])) {
            $cart[$idmenu]['jumlah']++;
        } else {
            $cart[$idmenu] = [
                'idmenu' => $menu->idmenu,
                'nama_menu' => $menu->nama_menu,
                'harga' => $menu->harga,
                'jumlah' => 1,
                'idvendor' => $menu->idvendor,
                'nama_vendor' => $menu->vendor->nama_vendor,
                'path_gambar' => $menu->path_gambar
            ];
        }
        
        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Menu ditambahkan ke keranjang!');
    }
    
    public function update(Request $request, $idmenu)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$idmenu])) {
            $cart[$idmenu]['jumlah'] = $request->jumlah;
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Keranjang diupdate');
    }
    
    public function remove($idmenu)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$idmenu])) {
            unset($cart[$idmenu]);
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Item dihapus');
    }
    
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Keranjang dikosongkan');
    }
}