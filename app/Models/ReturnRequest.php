<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ReturnRequest extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'lending_id',
        'requested_date',
        'status',
        'member_notes',
        'librarian_notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'processed_at' => 'datetime',
    ];

    public function lending()
    {
        return $this->belongsTo(BookLending::class, 'lending_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['lending_id', 'requested_date', 'status', 'member_notes', 'librarian_notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
