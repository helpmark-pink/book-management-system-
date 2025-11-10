<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadingRecord extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'current_page',
        'total_pages',
        'progress_percentage',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'current_page' => 'integer',
        'total_pages' => 'integer',
        'progress_percentage' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($record) {
            if ($record->total_pages > 0) {
                $record->progress_percentage = ($record->current_page / $record->total_pages) * 100;
            }
        });
    }
}
