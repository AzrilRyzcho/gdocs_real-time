<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    public $timestamps = false; // hanya pakai created_at custom

    protected $fillable = [
        'document_id',
        'title',
        'content',
        'editor_name',
        'editor_color',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
