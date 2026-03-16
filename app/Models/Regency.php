<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    protected $table = 'reg_regencies';
    public $incrementing = false;
    protected $keyType = 'string';

    // Relasi balik ke Provinsi
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    // Relasi ke Kecamatan
    public function districts()
    {
        return $this->hasMany(District::class, 'regency_id', 'id');
    }
}
