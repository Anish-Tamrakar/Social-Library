<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title', 'summary', 'author_id', 'genre', 'cover_image', 'pdf_path', 'downloadable', 'status'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
