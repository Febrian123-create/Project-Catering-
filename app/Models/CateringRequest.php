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
        'status',
        'nama_menu',
        'deskripsi',
        'asal_daerah',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
