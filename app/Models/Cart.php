<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'menu_id',
        'qty',
        'bundle_id',
        'bundle_name',
        'bundle_price',
    ];

    protected $casts = [
        'qty' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    // Get subtotal
    public function getSubtotalAttribute(): int
    {
        if ($this->bundle_id) {
            return $this->bundle_price * $this->qty;
        }
        return $this->menu ? $this->menu->harga * $this->qty : 0;
    }
}
