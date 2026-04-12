<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\Student; 
use App\Models\User;

class ActivityLogController extends Controller
{
    public function index()
    {
        auth()->user()->last_activity_read_at = now();
        auth()->user()->save();

        $query = ActivityLog::with('causer')->latest();

        if (request('type')) {
            $query->where('log_name', request('type'));
        }

        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('causer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if (request('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }
        if (request('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }

        $logs = $query->paginate(20)->appends(request()->query());

        return view('activity-logs.index', compact('logs'));
    }

    public function show($id)
    {
        $log = ActivityLog::with('causer')->findOrFail($id);
        return view('activity-logs.show', compact('log'));
    }

    public function destroy($id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->delete();

        return back()->with('success', 'Activity log deleted successfully.');
    }
}