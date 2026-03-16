<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'reg_districts';
    public $incrementing = false;
    protected $keyType = 'string';

    // Relasi balik ke Kota
    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id', 'id');
    }

    // Relasi ke Kelurahan
    public function villages()
    {
        return $this->hasMany(Village::class, 'district_id', 'id');
    }
}
