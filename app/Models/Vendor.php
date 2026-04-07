<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $primaryKey = 'idvendor';
    public $timestamps = false;
    
    protected $fillable = ['nama_vendor', 'user_id'];  // ← tambah user_id
    
    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    // Relasi ke menu
    public function menus()
    {
        return $this->hasMany(Menu::class, 'idvendor', 'idvendor');
    }
}