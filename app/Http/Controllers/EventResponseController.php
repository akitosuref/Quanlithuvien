<?php

namespace App\Http\Controllers;

use App\Models\EventResponse;
use App\Models\LibraryEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventResponseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:library_events,id',
            'response_type' => 'required|in:interested,attending,not_attending',
            'comment' => 'nullable|string',
        ]);

        $event = LibraryEvent::findOrFail($validated['event_id']);

        if ($event->max_participants) {
            $attendingCount = EventResponse::where('event_id', $event->id)
                ->where('response_type', 'attending')
                ->count();
            
            if ($validated['response_type'] === 'attending' && $attendingCount >= $event->max_participants) {
                return redirect()->back()
                    ->with('error', 'Sự kiện đã đạt số lượng người tham gia tối đa.');
            }
        }

        $validated['member_id'] = Auth::id();

        EventResponse::updateOrCreate(
            [
                'event_id' => $validated['event_id'],
                'member_id' => Auth::id(),
            ],
            $validated
        );

        return redirect()->back()
            ->with('success', 'Phản hồi của bạn đã được ghi nhận.');
    }

    public function update(Request $request, EventResponse $eventResponse)
    {
        if ($eventResponse->member_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $eventResponse->update($validated);

        return redirect()->back()
            ->with('success', 'Đánh giá của bạn đã được cập nhật.');
    }

    public function destroy(EventResponse $eventResponse)
    {
        if ($eventResponse->member_id !== Auth::id()) {
            abort(403);
        }

        $eventResponse->delete();

        return redirect()->back()
            ->with('success', 'Phản hồi đã được xóa.');
    }
}
