<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PhieuMuon extends Model
{
    use LogsActivity;

    protected $table = 'book_lendings';

    protected $fillable = [
        'member_id',
        'book_item_id',
        'borrowed_date',
        'due_date',
        'return_date',
    ];

    protected $casts = [
        'borrowed_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
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

    public function returnRequests()
    {
        return $this->hasMany(ReturnRequest::class, 'lending_id');
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