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
        'tipe',
        'nama_paket',
        'harga_paket',
        'deskripsi_paket',
        'foto_paket',
        'tgl_tersedia',
        'product_id',
    ];

    protected $casts = [
        'tgl_tersedia' => 'date',
        'harga_paket' => 'integer',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    // Many-to-many relationship for paket menus
    public function products()
    {
        return $this->belongsToMany(Product::class, 'menu_product', 'menu_id', 'product_id');
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

    // Helper methods
    public function isPaket(): bool
    {
        return $this->tipe === 'paket';
    }

    public function isSatuan(): bool
    {
        return $this->tipe === 'satuan';
    }

    // Dynamic accessors based on tipe
    public function getNamaDisplayAttribute(): string
    {
        if ($this->isPaket()) {
            return $this->nama_paket ?? 'Paket Menu';
        }
        return $this->product ? $this->product->nama : '-';
    }

    public function getHargaAttribute()
    {
        if ($this->isPaket()) {
            return $this->harga_paket ?? 0;
        }
        return $this->product ? $this->product->harga : 0;
    }

    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getDeskripsiDisplayAttribute(): string
    {
        if ($this->isPaket()) {
            return $this->deskripsi_paket ?? '';
        }
        return $this->product ? ($this->product->deskripsi ?? '') : '';
    }

    public function getFotoDisplayAttribute(): ?string
    {
        if ($this->isPaket()) {
            return $this->foto_paket;
        }
        return $this->product ? $this->product->foto : null;
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
