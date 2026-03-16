<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $table = 'reg_villages';
    public $incrementing = false;
    protected $keyType = 'string';

    // Relasi balik ke Kecamatan
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
}
