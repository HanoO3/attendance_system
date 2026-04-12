<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\Student;
use App\Models\Department;
use App\Models\ProfileRequest;
use App\Models\Attendance;
use App\Models\Notification; 
use App\Models\ActivityLog; 

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,super_admin']);
    }

    public function dashboard()
    {
        $studentsCount = Student::count();
        $departmentsCount = Department::count();
        $teachersCount = User::where('role', 'teacher')->count();
        
        $activeStudents = Student::has('attendances')->count();

        $activeStudentsData = Student::whereHas('attendances')
                                ->with('department')
                                ->get();

        $presentCount = Attendance::where('status', 'present')->count();
        $absentCount = Attendance::where('status', 'absent')->count();
        $lateCount = Attendance::where('status', 'late')->count();
        $leaveCount = Attendance::where('status', 'leave')->count();

        return view('dashboard', compact(
            'studentsCount', 
            'departmentsCount', 
            'teachersCount', 
            'activeStudents',
            'activeStudentsData', 
            'presentCount', 
            'absentCount', 
            'lateCount', 
            'leaveCount'
        ));
    }

    public function requests()
    {
        auth()->user()->last_profile_read_at = now();
        auth()->user()->save();

        $status = request('status');

        $query = ProfileRequest::with('department')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $requests = $query->get();

        $countAll = ProfileRequest::count();
        $countPending = ProfileRequest::where('status', 'pending')->count();
        $countApproved = ProfileRequest::where('status', 'approved')->count();
        $countRejected = ProfileRequest::where('status', 'rejected')->count();

        return view('admin.requests', compact('requests', 'status', 'countAll', 'countPending', 'countApproved', 'countRejected'));
    }

    public function viewLeaves()
    {
        auth()->user()->last_leave_read_at = now();
        auth()->user()->save();

        $countPending = LeaveRequest::where('status', 'pending')->count();

        $query = LeaveRequest::with('student.department')->latest();

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $leaves = $query->paginate(15);

        return view('admin.leave_requests', compact('leaves', 'countPending'));
    }

    public function approveLeave($id)
    {
        $leave = LeaveRequest::find($id);
        if($leave){
            $leave->status = 'approved';
            $leave->student_seen = false; 
            $leave->save();

            if($leave->student && $leave->student->user_id) {
                Notification::create([
                    'user_id' => $leave->student->user_id,
                    'title' => 'Leave Approved',
                    'message' => 'Your leave request for ' . $leave->leave_date . ' has been approved.',
                    'is_read' => false 
                ]);
            }
        }
        return back()->with('success', 'Leave Approved.');
    }

    public function rejectLeave($id)
    {
        $leave = LeaveRequest::find($id);
        if($leave){
            $leave->status = 'rejected';
            $leave->student_seen = false;
            $leave->save();

            if($leave->student && $leave->student->user_id) {
                Notification::create([
                    'user_id' => $leave->student->user_id,
                    'title' => 'Leave Rejected',
                    'message' => 'Your leave request for ' . $leave->leave_date . ' has been rejected.',
                    'is_read' => false
                ]);
            }
        }
        return back()->with('success', 'Leave Rejected.');
    }

    public function deleteLeave($id)
    {
        $leave = LeaveRequest::find($id);
        
        if ($leave) {
            $leave->delete();
            return back()->with('success', 'Leave request deleted successfully.');
        }
        
        return back()->with('error', 'Leave request not found.');
    }

    public function deleteRequest($id)
    {
        $request = ProfileRequest::find($id);
        
        if ($request) {
            $request->update(['status' => 'rejected']);
            return back()->with('success', 'Request rejected successfully.');
        }

        return back()->with('error', 'Request not found.');
    }

    public function destroyProfileRequest($id)
    {
        $request = ProfileRequest::find($id);
        
        if ($request) {
            $request->delete();
            return back()->with('success', 'Request deleted permanently.');
        }

        return back()->with('error', 'Request not found.');
    }
}