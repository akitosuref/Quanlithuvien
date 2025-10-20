<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LibraryEvent extends Model
{
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_type',
        'location',
        'max_participants',
        'created_by',
        'status',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(EventResponse::class, 'event_id');
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(EventResponse::class, 'event_id')
            ->where('response_type', 'attending');
    }
}
