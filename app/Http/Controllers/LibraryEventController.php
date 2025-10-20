<?php

namespace App\Http\Controllers;

use App\Models\LibraryEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('librarian');
    }

    public function index()
    {
        $events = LibraryEvent::with('creator', 'responses')
            ->orderBy('event_date', 'desc')
            ->paginate(10);
        
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date|after:now',
            'event_type' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published,cancelled,completed',
        ]);

        $validated['created_by'] = Auth::id();

        LibraryEvent::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Sự kiện đã được tạo thành công.');
    }

    public function show(LibraryEvent $event)
    {
        $event->load('creator', 'responses.member');
        
        return view('events.show', compact('event'));
    }

    public function edit(LibraryEvent $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, LibraryEvent $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_type' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published,cancelled,completed',
        ]);

        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Sự kiện đã được cập nhật thành công.');
    }

    public function destroy(LibraryEvent $event)
    {
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Sự kiện đã được xóa thành công.');
    }
}
