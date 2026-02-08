<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_detail';
    protected $primaryKey = 'detail_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'detail_id',
        'order_id',
        'menu_id',
        'qty',
        'status_kirim',
        'tanggal_kirim',
    ];

    protected $casts = [
        'tanggal_kirim' => 'date',
        'qty' => 'integer',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    // Get subtotal (price from menu's product)
    public function getSubtotalAttribute(): int
    {
        return $this->menu ? $this->menu->harga * $this->qty : 0;
    }

    // Status badge
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status_kirim) {
            'pending' => '<span class="badge bg-warning">Menunggu</span>',
            'shipped' => '<span class="badge bg-info">Dikirim</span>',
            'delivered' => '<span class="badge bg-success">Terkirim</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    // Generate new detail ID
    public static function generateDetailId(): string
    {
        $last = self::orderBy('detail_id', 'desc')->first();
        if ($last) {
            $lastNum = (int) substr($last->detail_id, 3);
            return 'DTL' . str_pad($lastNum + 1, 9, '0', STR_PAD_LEFT);
        }
        return 'DTL000000001';
    }
}
