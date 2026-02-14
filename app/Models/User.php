<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Cari user terakhir yang ID-nya punya format 'B-'
            $lastUser = self::where('user_id', 'LIKE', 'B-%')
                ->orderBy('user_id', 'desc')
                ->first();

            if (!$lastUser) {
                // Kalau benar-benar kosong, mulai dari B-001
                $model->user_id = 'B-001';
            } else {
                // Ambil angka setelah 'B-' (substr mulai dari indeks ke-2)
                // Contoh: 'B-001' -> ambil '001', lalu jadikan integer (1)
                $lastNumber = (int) substr($lastUser->user_id, 2);

                // Tambah 1 dan kasih nol di depan (pad) biar tetap 3 digit
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

                // Gabungkan jadi B-002, B-003, dst.
                $model->user_id = 'B-' . $newNumber;
            }
        });
    }
    protected $fillable = [
        'nama',
        'kontak',
        'alamat_default',
        'username',
        'otp_code',
        'is_verified',
        'role',
        'password',
        'foto',
    ];

    // Relationships
    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id', 'user_id');
    }

    // Helper methods
    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    public function isSeller()
    {
        // Allow Admin to access Seller features (Dashboard)
        return strtolower($this->role) === 'seller' || strtolower($this->role) === 'admin';
    }

    public function isAdmin()
    {
        return strtolower($this->role) === 'admin';
    }


}
