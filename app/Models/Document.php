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
        'is_favorite',
        'is_archived',
        'last_editor_id',
        'last_editor_name',
        'last_editor_color',
        'last_edited_at',
    ];

    protected $casts = [
        'last_edited_at' => 'datetime',
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

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)->orderByDesc('created_at');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function shares()
    {
        return $this->hasMany(DocumentShare::class);
    }

    public function sharedUsers()
    {
        return $this->belongsToMany(User::class, 'document_shares')->withPivot('role')->withTimestamps();
    }
}
