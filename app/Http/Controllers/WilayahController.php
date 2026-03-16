<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class WilayahController extends Controller
{
    public function index() {
        $provinces = Province::all();
        return view('wilayah.index', compact('provinces'));
    }

    public function getKota($id) {
        return response()->json(Regency::where('province_id', $id)->get());
    }

    public function getKecamatan($id) {
        return response()->json(District::where('regency_id', $id)->get());
    }

    public function getKelurahan($id) {
        return response()->json(Village::where('district_id', $id)->get());
    }
}
