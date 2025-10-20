<?php

namespace App\Http\Controllers;

use App\Models\EventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->hasRole('librarian')) {
            $requests = EventRequest::with('member', 'reviewer')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $requests = EventRequest::where('member_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        
        return view('event-requests.index', compact('requests'));
    }

    public function create()
    {
        return view('event-requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requested_event_date' => 'nullable|date|after:now',
        ]);

        $validated['member_id'] = Auth::id();

        EventRequest::create($validated);

        return redirect()->route('event-requests.index')
            ->with('success', 'Yêu cầu sự kiện đã được gửi thành công.');
    }

    public function show(EventRequest $eventRequest)
    {
        if (!Auth::user()->hasRole('librarian') && $eventRequest->member_id !== Auth::id()) {
            abort(403);
        }

        $eventRequest->load('member', 'reviewer');
        
        return view('event-requests.show', compact('eventRequest'));
    }

    public function review(EventRequest $eventRequest)
    {
        $this->authorize('review', $eventRequest);
        
        return view('event-requests.review', compact('eventRequest'));
    }

    public function updateReview(Request $request, EventRequest $eventRequest)
    {
        $this->authorize('review', $eventRequest);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'review_note' => 'nullable|string',
        ]);

        $validated['reviewed_by'] = Auth::id();
        $validated['reviewed_at'] = now();

        $eventRequest->update($validated);

        return redirect()->route('event-requests.index')
            ->with('success', 'Yêu cầu đã được xem xét.');
    }

    public function destroy(EventRequest $eventRequest)
    {
        if (!Auth::user()->hasRole('librarian') && $eventRequest->member_id !== Auth::id()) {
            abort(403);
        }

        $eventRequest->delete();

        return redirect()->route('event-requests.index')
            ->with('success', 'Yêu cầu đã được xóa.');
    }
}
