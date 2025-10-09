<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuMuon extends Model
{
    protected $table = 'book_lendings';

    protected $fillable = [
        'member_id',
        'book_item_id',
        'borrowed_date',
        'due_date',
        'return_date',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function bookItem()
    {
        return $this->belongsTo(BookItem::class, 'book_item_id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}