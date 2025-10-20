<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer', 'subject')
            ->latest();

        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        $activities = $query->paginate(20);

        return view('activity-logs.index', compact('activities'));
    }

    public function show(Activity $activity)
    {
        $activity->load('causer', 'subject');
        return view('activity-logs.show', compact('activity'));
    }
}
