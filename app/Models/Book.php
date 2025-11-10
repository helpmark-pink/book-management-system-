<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'isbn',
        'title',
        'author',
        'publisher',
        'published_date',
        'page_count',
        'cover_image',
        'description',
    ];

    protected $casts = [
        'published_date' => 'date',
    ];

    public function readingRecords()
    {
        return $this->hasMany(ReadingRecord::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, ReadingRecord::class);
    }
}
