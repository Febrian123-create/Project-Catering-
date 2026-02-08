<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';
    protected $primaryKey = 'menu_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'menu_id',
        'tgl_tersedia',
        'product_id',
    ];

    protected $casts = [
        'tgl_tersedia' => 'date',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'menu_id', 'menu_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'menu_id', 'menu_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'menu_id', 'menu_id');
    }

    // Get price from product
    public function getHargaAttribute()
    {
        return $this->product ? $this->product->harga : 0;
    }

    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Check if menu is available
    public function isAvailable(): bool
    {
        return $this->tgl_tersedia->isToday() || $this->tgl_tersedia->isFuture();
    }

    // Generate ID
    public static function generateMenuId(): string
    {
        $last = self::orderBy('menu_id', 'desc')->first();
        if ($last) {
            $num = (int) substr($last->menu_id, 3);
            return 'MNU' . str_pad($num + 1, 9, '0', STR_PAD_LEFT);
        }
        return 'MNU000000001';
    }
}
