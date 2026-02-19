<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';
    protected $primaryKey = 'order_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'user_id',
        'alamat_pengiriman',
        'notes',
        'tgl_pesan',
        'total_bayar',
        'status_pembayaran',
        'invoice_number',
    ];

    protected $casts = [
        'tgl_pesan' => 'date',
        'total_bayar' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }

    // Formatted total
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_bayar, 0, ',', '.');
    }

    // Status badges
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status_pembayaran) {
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'paid' => '<span class="badge bg-success">Lunas</span>',
            'cancelled' => '<span class="badge bg-danger">Dibatalkan</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    // Generate new order ID
    public static function generateOrderId(): string
    {
        $lastOrder = self::orderBy('order_id', 'desc')->first();
        if ($lastOrder) {
            $lastNum = (int) substr($lastOrder->order_id, 3);
            return 'ORD' . str_pad($lastNum + 1, 9, '0', STR_PAD_LEFT);
        }
        return 'ORD000000001';
    }
}
