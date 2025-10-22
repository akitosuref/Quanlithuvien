<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BorrowRequest extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'member_id',
        'book_item_id',
        'requested_date',
        'expected_borrow_date',
        'status',
        'member_notes',
        'librarian_notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'expected_borrow_date' => 'date',
        'processed_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function bookItem()
    {
        return $this->belongsTo(BookItem::class, 'book_item_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['member_id', 'book_item_id', 'requested_date', 'expected_borrow_date', 'status', 'member_notes', 'librarian_notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
