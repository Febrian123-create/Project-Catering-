<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'review';
    protected $primaryKey = 'review_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'review_id',
        'user_id',
        'menu_id',
        'bintang',
        'isi_review',
        'tgl_review',
    ];

    protected $casts = [
        'tgl_review' => 'date',
        'bintang' => 'integer',
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

    // Star display
    public function getStarsAttribute(): string
    {
        $stars = str_repeat('★', $this->bintang);
        $empty = str_repeat('☆', 5 - $this->bintang);
        return $stars . $empty;
    }

    // Generate new review ID
    public static function generateReviewId(): string
    {
        $last = self::orderBy('review_id', 'desc')->first();
        if ($last) {
            $num = (int) substr($last->review_id, 3);
            return 'REV' . str_pad($num + 1, 9, '0', STR_PAD_LEFT);
        }
        return 'REV000000001';
    }
}
