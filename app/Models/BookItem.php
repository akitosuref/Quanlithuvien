<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'rack_id',
        'barcode',
        'format',
        'status',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }

    public function lendings()
    {
        return $this->hasMany(BookLending::class);
    }

    public function reservations()
    {
        return $this->hasMany(BookReservation::class);
    }

    public function currentReservation()
    {
        return $this->hasOne(BookReservation::class)->where('status', 'WAITING');
    }

    public function isAvailable()
    {
        return $this->status === 'AVAILABLE';
    }
}