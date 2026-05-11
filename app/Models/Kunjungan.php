<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';
    protected $primaryKey = 'id_kunjungan';
    public $timestamps = false;
    
    protected $fillable = [
        'id_toko', 'nama_sales', 'latitude_sales', 
        'longitude_sales', 'accuracy_sales', 'jarak_hitung', 'status'
    ];
    
    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id_toko');
    }
}