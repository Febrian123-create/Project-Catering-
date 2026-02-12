<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
        'nama_menu',
        'jumlah_porsi',
        'tanggal_kebutuhan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
