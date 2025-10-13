<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'subject',
        'publication_date',
    ];

    public function bookItems()
    {
        return $this->hasMany(BookItem::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}