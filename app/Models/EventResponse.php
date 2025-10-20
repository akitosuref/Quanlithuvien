<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventResponse extends Model
{
    protected $fillable = [
        'event_id',
        'member_id',
        'response_type',
        'comment',
        'rating',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(LibraryEvent::class, 'event_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
