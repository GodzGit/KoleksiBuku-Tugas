<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    public $timestamps = false;
    
    protected $fillable = ['nama', 'timestamp', 'total', 'metode_bayar', 'status_bayar', 'user_id'];
    
    protected $casts = [
        'timestamp' => 'datetime',
        'status_bayar' => 'integer',
        'metode_bayar' => 'integer',
    ];
    
    // Status constants
    const STATUS_PENDING = 0;
    const STATUS_LUNAS = 1;
    const STATUS_BATAL = 2;
    
    // Metode constants
    const METODE_VA = 1;
    const METODE_QRIS = 2;
    
    // Relasi ke detail pesanan
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan', 'idpesanan');
    }
    
    // Relasi ke menu (via detail)
    public function menus()
    {
        return $this->belongsToMany(
            Menu::class, 
            'detail_pesanan', 
            'idpesanan', 
            'idmenu'
        )->withPivot('jumlah', 'harga', 'subtotal', 'catatan');
    }
    
    // Helper methods
    public function isLunas()
    {
        return $this->status_bayar === self::STATUS_LUNAS;
    }
    
    public function isPending()
    {
        return $this->status_bayar === self::STATUS_PENDING;
    }
    
    public function getStatusTextAttribute()
    {
        return match($this->status_bayar) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_LUNAS => 'Lunas ✅',
            self::STATUS_BATAL => 'Batal ❌',
            default => 'Unknown'
        };
    }
    
    public function getMetodeTextAttribute()
    {
        return match($this->metode_bayar) {
            self::METODE_VA => 'Virtual Account',
            self::METODE_QRIS => 'QRIS',
            default => '-'
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}