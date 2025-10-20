<?php

namespace App\Http\Controllers;

use App\Models\LibraryEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $events = LibraryEvent::where('status', 'published')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->with('creator', 'responses')
            ->paginate(10);
        
        return view('member-events.index', compact('events'));
    }

    public function show(LibraryEvent $event)
    {
        if ($event->status !== 'published' && !Auth::user()->hasRole('librarian')) {
            abort(403);
        }

        $event->load('creator', 'responses.member');
        $userResponse = $event->responses()->where('member_id', Auth::id())->first();
        
        return view('member-events.show', compact('event', 'userResponse'));
    }
}
