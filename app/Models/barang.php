<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    protected $guarded = []; // Mengizinkan semua kolom diisi
    public $timestamps = false;

    protected $fillable = [
        'nama_barang',
        'harga'
    ];
}
