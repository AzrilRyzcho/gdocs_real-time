<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['document_id', 'user_id', 'parent_id', 'body', 'resolved'];

    public function document() { return $this->belongsTo(Document::class); }
    public function user()     { return $this->belongsTo(User::class); }
    public function replies()  { return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at'); }
    public function parent()   { return $this->belongsTo(Comment::class, 'parent_id'); }
}
