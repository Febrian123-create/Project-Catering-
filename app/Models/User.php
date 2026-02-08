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

    protected $fillable = [
        'user_id',
        'nama',
        'kontak',
        'alamat_default',
        'username',
        'role',
        'password',
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

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    // Generate ID
    public static function generateUserId(): string
    {
        // Format: USR000000001
        $last = self::orderBy('user_id', 'desc')->first();
        if ($last) {
            $num = (int) substr($last->user_id, 3);
            return 'USR' . str_pad($num + 1, 9, '0', STR_PAD_LEFT);
        }
        return 'USR000000001';
    }
}
