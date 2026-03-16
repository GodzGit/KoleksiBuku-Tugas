<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'reg_provinces';
    public $incrementing = false;
    protected $keyType = 'string';

    public function regencies() {
        return $this->hasMany(Regency::class, 'province_id', 'id');
    }
}
