<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BookLending extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'member_id',
        'book_item_id',
        'borrowed_date',
        'due_date',
        'return_date',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function bookItem()
    {
        return $this->belongsTo(BookItem::class);
    }

    public function isOverdue()
    {
        return is_null($this->return_date) && now()->gt($this->due_date);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['member_id', 'book_item_id', 'borrowed_date', 'due_date', 'return_date'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}