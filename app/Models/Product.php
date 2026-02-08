<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'product_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'nama',
        'harga',
        'deskripsi',
        'foto',
    ];

    protected $casts = [
        'harga' => 'integer',
    ];

    // Relationships
    public function menus()
    {
        return $this->hasMany(Menu::class, 'product_id', 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }

    // Accessor for formatted price
    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Get average rating
    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('bintang') ?? 0;
    }

    // Generate ID
    public static function generateProductId(): string
    {
        // Format: PRD000000001
        $last = self::orderBy('product_id', 'desc')->first();
        if ($last) {
            $num = (int) substr($last->product_id, 3);
            return 'PRD' . str_pad($num + 1, 9, '0', STR_PAD_LEFT);
        }
        return 'PRD000000001';
    }
}
