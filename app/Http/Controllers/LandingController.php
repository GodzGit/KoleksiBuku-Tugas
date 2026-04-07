<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Vendor;

class LandingController extends Controller
{
    public function index()
    {
        $menus = Menu::with('vendor')->get();
        $vendors = Vendor::all();
        
        return view('landing.index', compact('menus', 'vendors'));
    }
    
    public function getMenusByVendor($idvendor)
    {
        $menus = Menu::where('idvendor', $idvendor)->get();
        return response()->json($menus);
    }
}