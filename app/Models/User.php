<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'guest_id', 'vendor_id'
    ];
    protected $primaryKey = 'id';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $attributes = [
        'role' => 'customer'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (!$user->email) {
                $lastGuest = User::whereNotNull('guest_id')
                    ->orderBy('id', 'desc')
                    ->first();
                $lastNumber = $lastGuest ? intval(substr($lastGuest->guest_id, 6)) : 0;
                $user->guest_id = 'Guest_' . str_pad($lastNumber + 1, 7, '0', STR_PAD_LEFT);
                $user->name = $user->guest_id;
            }
        });
    }

    // Tambahkan setelah boot() method atau di akhir class

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'idvendor');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isVendor()
    {
        return $this->role === 'vendor';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isSales()
    {
        return $this->role === 'sales';
    }

    

}
