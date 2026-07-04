<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Department; 
use App\Models\Attendance;
use App\Models\Subject;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $departments = Department::all();
        $semesters = ['1st','2nd','3rd','4th','5th','6th','7th','8th'];
        
        $department_id = request('department_id');
        $semester = request('semester');
        $session = request('session');
        $date = request('date') ?? date('Y-m-d');
        $subject_id = request('subject_id');

        $students = null;
        $subject = null;
        $attendanceData = collect();

        if ($department_id && $semester && $session) {

            $cleanSession = trim($session);
            $cleanSession = str_replace(["\xe2\x80\x93", "\xe2\x80\x94", "\xc2\xad", ' '], '-', $cleanSession);
            $cleanSession = preg_replace('/-+/', '-', $cleanSession);

            $students = Student::where('department_id', $department_id)
                               ->where('semester', $semester)
                               ->whereRaw("REPLACE(REPLACE(REPLACE(session, '\xe2\x80\x93', '-'), '\xe2\x80\x94', '-'), ' ', '') = ?", [$cleanSession])
                               ->orderBy('roll_number')
                               ->get();

            if ($students->count() === 0) {
                $yearParts = explode('-', $cleanSession);
                if (count($yearParts) === 2) {
                    $students = Student::where('department_id', $department_id)
                                       ->where('semester', $semester)
                                       ->where('session', 'LIKE', '%' . trim($yearParts[0]) . '%')
                                       ->where('session', 'LIKE', '%' . trim($yearParts[1]) . '%')
                                       ->orderBy('roll_number')
                                       ->get();
                }
            }

            if ($subject_id) {
                $subject = Subject::find($subject_id);
            }

            if ($students->count() > 0) {
                $attendanceData = Attendance::where('attendance_date', $date)
                    ->whereIn('student_id', $students->pluck('id'))
                    ->when($subject_id, function($query) use ($subject_id) {
                        return $query->where('subject_id', $subject_id);
                    })
                    ->get()
                    ->keyBy('student_id');
            }
        }

        return view('attendance.index', compact(
            'departments', 'semesters', 'students', 'subject', 
            'department_id', 'semester', 'session', 'date', 
            'attendanceData'
        ));
    }

public function store(Request $request)
{
    $request->validate([
        'department_id' => 'required', 
        'attendance_date' => 'required|date',
        'status' => 'required|array',
    ]);

    $date = $request->attendance_date;
    $department_id = $request->department_id;
    $session = $request->session ?? null;
    $subject_id = $request->subject_id ?? null;
    
    $department = Department::find($department_id);
    $departmentName = $department ? $department->name : 'N/A';

    $subject = Subject::find($subject_id);
    $subjectName = $subject ? $subject->name : 'N/A';
    $semester = $subject ? $subject->semester : 'N/A';
    $presentCount = 0; $absentCount = 0; $lateCount = 0; $leaveCount = 0;

    foreach ($request->status as $student_id => $status) {
        if ($status == 'present') $presentCount++;
        elseif ($status == 'absent') $absentCount++;
        elseif ($status == 'late') $lateCount++;
        elseif ($status == 'leave') $leaveCount++;

        Attendance::updateOrCreate(
            [
                'student_id' => $student_id,
                'attendance_date' => $date,
                'subject_id' => $subject_id, 
            ],
            [
                'department_id' => $department_id,
                'department_name' => $departmentName, 
                'session' => $session,
                'status' => $status,
                'remarks' => $request->remarks[$student_id] ?? null
            ]
        );
    }

    $logDetails = [
        'date'       => $date,
        'department' => $departmentName,
        'subject'    => $subjectName,
        'semester'   => $semester,
        'session'    => $session,
        'present'    => $presentCount,
        'absent'     => $absentCount,
        'late'       => $lateCount,
        'leave'      => $leaveCount,
        'total'      => count($request->status)
    ];

    ActivityLog::create([
        'log_name' => 'Attendance',
        'description' => "Marked attendance for Subject: {$subjectName}",
        'causer_id' => auth()->id(),
        'causer_type' => User::class,
        'properties' => $logDetails
    ]);

    return redirect()->back()->with('success', 'Attendance saved successfully!');
}
}