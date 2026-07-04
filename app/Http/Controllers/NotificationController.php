<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,super_admin'])->except(['studentNotifications']);
    }

    public function create()
    {
        $students = Student::all();
        $teachers = Teacher::all();
        return view('notifications.create', compact('students', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required',
            'recipient' => 'required',
        ]);

        $userIds = [];

        if ($request->recipient == 'all_students' || $request->recipient == 'all') {
            $userIds = array_merge($userIds, Student::pluck('user_id')->toArray());
        }
        
        if ($request->recipient == 'all_teachers' || $request->recipient == 'all') {
            $userIds = array_merge($userIds, Teacher::pluck('user_id')->toArray());
        }

        $userIds = array_filter($userIds);

        foreach ($userIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'title' => $request->title,
                'message' => $request->message,
                'is_read' => false,
            ]);
        }

        $propertiesData = [
            'title' => $request->title,
            'message' => $request->message,
            'sent_to' => $request->recipient, 
            'total_sent' => count($userIds)
        ];

        ActivityLog::create([
            'log_name' => 'Notification',
            'description' => 'Sent notification: ' . $request->title,
            'subject_type' => Notification::class,
            'causer_id' => auth()->id(),
            'causer_type' => User::class,
            'properties' => json_encode($propertiesData) 
        ]);

        return back()->with('success', 'Notification sent successfully!');
    }

    public function history()
    {
        $logs = ActivityLog::where('log_name', 'Notification')->latest()->paginate(20);
        return view('notifications.history', compact('logs'));
    }

    public function destroyHistory($id)
    {
        $log = ActivityLog::findOrFail($id);
        
        if ($log->log_name == 'Notification') {
            $log->delete();
            return back()->with('success', 'Notification history deleted successfully.');
        }

        return back()->with('error', 'Invalid action.');
    }

    public function studentNotifications()
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        Notification::where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10); 

        return view('students.notifications', compact('notifications', 'student'));
    }
}