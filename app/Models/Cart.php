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
        return $this->menu ? $this->menu->harga * $this->qty : 0;
    }
}
