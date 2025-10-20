<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Book extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'isbn',
        'title',
        'subject',
        'publication_date',
        'cover_image',
    ];

    public function bookItems()
    {
        return $this->hasMany(BookItem::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['isbn', 'title', 'subject', 'publication_date', 'cover_image'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}