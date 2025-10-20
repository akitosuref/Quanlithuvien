<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address_id',
        'account_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }
    public function isLibrarian()
    {
        return $this->role === 'librarian';
    }

    public function isMember()
    {
        return $this->role === 'member';
    }

    public function lendings()
    {
        return $this->hasMany(BookLending::class, 'member_id');
    }

    public function reservations()
    {
        return $this->hasMany(BookReservation::class, 'member_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function libraryCard()
    {
        return $this->hasOne(LibraryCard::class, 'user_id');
    }

    public function canCheckoutBook()
    {
        $currentLendings = $this->lendings()->whereNull('return_date')->count();
        return $currentLendings < 10;
    }

    public function getCurrentLendings()
    {
        return $this->lendings()
            ->whereNull('return_date')
            ->with(['bookItem.book', 'bookItem.rack'])
            ->orderBy('due_date', 'asc')
            ->get();
    }

    public function getLendingHistory()
    {
        return $this->lendings()
            ->with(['bookItem.book'])
            ->orderBy('borrowed_date', 'desc')
            ->get();
    }

    public function getActiveReservations()
    {
        return $this->reservations()
            ->whereIn('status', ['WAITING', 'PROCESSING'])
            ->with(['bookItem.book'])
            ->orderBy('reservation_date', 'desc')
            ->get();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role', 'account_status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}