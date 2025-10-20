<?php

namespace App\Policies;

use App\Models\EventRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventRequestPolicy
{
    public function review(User $user, EventRequest $eventRequest): bool
    {
        return $user->hasRole('librarian');
    }
}
