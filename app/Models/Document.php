<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'share_token',
        'user_id',
    ];

    protected static function booted(): void
    {
        static::creating(function ($doc) {
            if (empty($doc->share_token)) {
                $doc->share_token = Str::random(12);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
