<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'book_item_id',
        'reservation_date',
        'status',
    ];

    protected $casts = [
        'reservation_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function bookItem()
    {
        return $this->belongsTo(BookItem::class);
    }

    public static function isReservedByAnyone($bookItem)
    {
        return self::where('book_item_id', $bookItem->id)
            ->where('status', 'WAITING')
            ->exists();
    }
}