<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\Subject;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $departments = Department::all();
        $semesters = ['1st','2nd','3rd','4th','5th','6th','7th','8th'];

        $students = null;
        $filter = false;
        $selectedSubject = null;
        
        $department_id = $request->department_id;
        $semester = $request->semester;
        $session = $request->session;

        if ($request->has('subject_id') && $request->subject_id != '') {
            
            $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after_or_equal:start_date',
                'session'    => 'required|string',
            ]);

            if ($user->role === 'teacher') {
                $isAssigned = $user->assignedSubjects()
                                   ->where('subject_id', $request->subject_id)
                                   ->wherePivot('session', $request->session)
                                   ->exists();
                if (!$isAssigned) {
                    abort(403, 'You are not authorized to view this report.');
                }
            }

            $filter = true;
            $selectedSubject = Subject::with('department')->find($request->subject_id);

            $sessionInput = str_replace(['–', ' '], ['-', ''], $request->session);

            $students = Student::where('department_id', $selectedSubject->department_id)
                               ->where('semester', $selectedSubject->semester)
                               ->whereRaw("REPLACE(session, ' ', '') = ?", [$sessionInput])
                               ->orderBy('roll_number')
                               ->get();

            foreach ($students as $student) {
                $records = Attendance::where('student_id', $student->id)
                    ->where('subject_id', $request->subject_id)
                    ->whereRaw("REPLACE(session, ' ', '') = ?", [$sessionInput])
                    ->whereBetween('attendance_date', [$request->start_date, $request->end_date])
                    ->get();

                $student->total_classes = $records->count();
                $student->present_count = $records->where('status', 'present')->count();
                $student->late_count    = $records->where('status', 'late')->count();
                $student->absent_count  = $records->where('status', 'absent')->count();
                $student->leave_count   = $records->where('status', 'leave')->count();
                $student->percentage    = ($student->total_classes > 0) 
                    ? round((($student->present_count + $student->late_count) / $student->total_classes) * 100, 1) : 0;
            }
        }

        return view('reports.index', compact(
            'departments', 'semesters', 'students', 'filter', 'selectedSubject', 
            'department_id', 'semester', 'session'
        ));
    }

    public function export(Request $request)
    {
        $request->validate([
            'subject_id' => 'required',
            'session' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $user = Auth::user(); 
        
        $subject_id = $request->subject_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $session = $request->session; 
        $sessionClean = str_replace(['–', ' '], ['-', ''], $session);

        $subject = Subject::with('department')->find($subject_id);

        if (!$subject) {
            return back()->with('error', 'Subject not found.');
        }

        if ($user->role === 'teacher') {
            $isAssigned = $user->assignedSubjects()
                               ->where('subject_id', $subject_id)
                               ->wherePivot('session', $session)
                               ->exists();
            if (!$isAssigned) {
                abort(403, 'Unauthorized action.');
            }
        }

        $department = $subject->department->name ?? 'N/A';
        $semester = $subject->semester;

        $students = Student::where('department_id', $subject->department_id)
                           ->where('semester', $semester)
                           ->whereRaw("REPLACE(session, ' ', '') = ?", [$sessionClean])
                           ->orderBy('roll_number')
                           ->get();

        foreach ($students as $student) {
            $records = Attendance::where('student_id', $student->id)
                ->where('subject_id', $subject_id)
                ->whereRaw("REPLACE(session, ' ', '') = ?", [$sessionClean])
                ->whereBetween('attendance_date', [$start_date, $end_date])
                ->get();

            $student->total_classes = $records->count();
            $student->present_count = $records->where('status', 'present')->count();
            $student->late_count    = $records->where('status', 'late')->count();
            $student->absent_count  = $records->where('status', 'absent')->count();
            $student->leave_count   = $records->where('status', 'leave')->count();
            $student->percentage    = ($student->total_classes > 0) 
                ? round((($student->present_count + $student->late_count) / $student->total_classes) * 100, 1) : 0;
        }

        $pdf = Pdf::loadView('reports.pdf', compact(
            'students', 'subject', 'department', 'semester', 'session', 'start_date', 'end_date'
        ));

        return $pdf->download('attendance_report.pdf');
    }
}