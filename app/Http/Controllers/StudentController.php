<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\ProfileRequest;
use App\Models\Attendance;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $query = Student::with('department');

        if (request('department_id')) {
            $query->where('department_id', request('department_id'));
        }

        $students = $query->get();
        $departments = Department::all();

        $sessions = Student::whereNotNull('session')
                    ->distinct()
                    ->pluck('session')
                    ->sort()
                    ->values();

        return view('students.index', compact('students', 'departments', 'sessions'));
    }

    public function create()
    {
        $departments = Department::all();
        $semesters = ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'];

        $requestData = null;
        if (request()->has('request_id')) {
            $requestData = ProfileRequest::find(request('request_id'));
        }

        return view('students.create', compact('departments', 'semesters', 'requestData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'roll_number'    => 'required|unique:students,roll_number',
            'department_id'  => 'required|exists:departments,id',
            'semester'       => 'required',
            'session'        => 'required',
            'course'         => 'required',
            'father_name'    => 'required|string|max:255',
            'contact_number' => 'required|digits:11|starts_with:03',
        ], [
            'contact_number.required'    => 'Contact number is required.',
            'contact_number.digits'      => 'Contact number must be exactly 11 digits.',
            'contact_number.starts_with' => 'Contact number must start with 03.',
            'roll_number.unique'         => 'This Roll Number is already registered.',
        ]);

        $department = Department::findOrFail($request->department_id);
        
        $email = strtolower($request->roll_number) . '@gmail.com';

        $session = str_replace('–', '-', $request->session);

        $studentData = [
            'student_name'    => $request->name,
            'father_name'     => $request->father_name,
            'roll_number'     => $request->roll_number,
            'contact_number'  => $request->contact_number,
            'department_id'   => $request->department_id,
            'semester'        => $request->semester,
            'session'         => $session,
            'course'          => $request->course,
        ];

        $logDescription = '';

        if ($request->filled('profile_request_id')) {
            
            $profileRequest = ProfileRequest::findOrFail($request->profile_request_id);

            $user = User::findOrFail($profileRequest->user_id);
            $user->update([
                'name'     => $request->name,
                'email'    => $email,
                'password' => Hash::make('password123'), 
                'role'     => 'student',
            ]);

            $studentData['user_id'] = $user->id;
            $student = Student::create($studentData);

            $profileRequest->update(['status' => 'approved']); 

            $logDescription = 'Created student profile via request: ' . $request->name;

        } else {
            
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'     => $request->name,
                    'password' => Hash::make('password123'), 
                    'role'     => 'student',
                ]
            );

            if (!$user->wasRecentlyCreated) {
                $user->update([
                    'name'     => $request->name,
                    'password' => Hash::make('password123'), 
                    'role'     => 'student',
                ]);
            }

            $studentData['user_id'] = $user->id;
            $student = Student::create($studentData);

            $logDescription = 'Added a new student: ' . $request->name;
        }

        ActivityLog::create([
            'log_name'     => 'Student Created',
            'description'  => $logDescription,
            'subject_id'   => $student->id,
            'subject_type' => Student::class,
            'causer_id'    => auth()->id(),
            'causer_type'  => User::class,
            'properties'   => json_encode([
                'student_name'    => $request->name,
                'roll_number'     => $request->roll_number,
                'father_name'     => $request->father_name,
                'department'      => $department->name ?? 'N/A',
                'semester'        => $request->semester,
                'session'         => $session,
                'course'          => $request->course,
                'contact_number'  => $request->contact_number,
            ])
        ]);

        return redirect()->route('students.index')
            ->with('success', 'Student added successfully. Login Email: ' . $email . ' | Password: password123');
    }

    public function edit(Student $student)
    {
        $departments = Department::all();
        $semesters = ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'];

        return view('students.edit', compact('student', 'departments', 'semesters'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'student_name'    => 'required|string|max:255',
            'department_id'   => 'required|exists:departments,id',
            'semester'        => 'required',
            'session'         => 'required',
            'course'          => 'required',
            'contact_number'  => 'required|digits:11|starts_with:03',
        ], [
            'contact_number.required'    => 'Contact number is required.',
            'contact_number.digits'      => 'Contact number must be exactly 11 digits.',
            'contact_number.starts_with' => 'Contact number must start with 03.',
        ]);

        $oldDepartment = $student->department;
        $oldData = [
            'student_name'    => $student->student_name,
            'roll_number'     => $student->roll_number,
            'father_name'     => $student->father_name,
            'department'      => $oldDepartment->name ?? 'N/A',
            'semester'        => $student->semester,
            'session'         => $student->session,
            'course'          => $student->course,
            'contact_number'  => $student->contact_number,
        ];

        $session = str_replace('–', '-', $request->session);

        $student->update([
            'student_name'    => $request->student_name,
            'department_id'   => $request->department_id,
            'semester'        => $request->semester,
            'session'         => $session,
            'course'          => $request->course,
            'contact_number'  => $request->contact_number,
        ]);

        $newDepartment = Department::find($request->department_id);
        $newData = [
            'student_name'    => $student->student_name,
            'roll_number'     => $student->roll_number,
            'father_name'     => $student->father_name,
            'department'      => $newDepartment->name ?? 'N/A',
            'semester'        => $student->semester,
            'session'         => $student->session,
            'course'          => $student->course,
            'contact_number'  => $student->contact_number,
        ];

        ActivityLog::create([
            'log_name'     => 'Student Updated',
            'description'  => 'Updated student details: ' . $student->student_name,
            'subject_id'   => $student->id,
            'subject_type' => Student::class,
            'causer_id'    => auth()->id(),
            'causer_type'  => User::class,
            'properties'   => json_encode([
                'old' => $oldData,
                'new' => $newData
            ])
        ]);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $studentName = $student->student_name;
        $studentId   = $student->id;
        $userId      = $student->user_id;

        $department = $student->department;

        ActivityLog::create([
            'log_name'     => 'Student Deleted',
            'description'  => 'Deleted student: ' . $studentName,
            'subject_id'   => $studentId,
            'subject_type' => Student::class,
            'causer_id'    => auth()->id(),
            'causer_type'  => User::class,
            'properties'   => json_encode([
                'student_name'    => $student->student_name,
                'roll_number'     => $student->roll_number,
                'father_name'     => $student->father_name,
                'department'      => $department->name ?? 'N/A',
                'semester'        => $student->semester,
                'session'         => $student->session,
                'course'          => $student->course,
                'contact_number'  => $student->contact_number,
            ])
        ]);

        $student->delete();

        if ($userId) {
            User::find($userId)?->delete();
        }

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
    
    public function dashboard()
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return view('students.dashboard', compact('student'));
        }

        $presentCount = Attendance::where('student_id', $student->id)->where('status', 'present')->count();
        $absentCount  = Attendance::where('student_id', $student->id)->where('status', 'absent')->count();
        $lateCount    = Attendance::where('student_id', $student->id)->where('status', 'late')->count();
        $leaveCount   = Attendance::where('student_id', $student->id)->where('status', 'leave')->count();

        $recentAttendances = Attendance::where('student_id', $student->id)
            ->orderBy('attendance_date', 'desc')
            ->take(5)
            ->get();

        return view('students.dashboard', compact(
            'student',
            'presentCount',
            'absentCount',
            'lateCount',
            'leaveCount',
            'recentAttendances'
        ));
    }

    public function myAttendance()
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        $attendances = Attendance::where('student_id', $student->id)
            ->orderBy('attendance_date', 'desc')
            ->get();

        return view('students.my_attendance', compact('attendances', 'student'));
    }

    public function sendRequest(Request $request)
    {
        $request->validate([
            'student_name'   => 'required',
            'father_name'    => 'required',
            'roll_no'        => 'required|unique:students,roll_number',
            'department_id'  => 'required|exists:departments,id',
            'semester'       => 'required',
            'session'        => 'required',
            'course'         => 'required', 
            'contact_number' => 'required|digits:11|starts_with:03',
        ], [
            'contact_number.required'    => 'Contact number is required.',
            'contact_number.digits'      => 'Contact number must be exactly 11 digits.',
            'contact_number.starts_with' => 'Contact number must start with 03.',
            'roll_no.unique'             => 'This Roll Number is already taken.',
        ]);

        $exists = ProfileRequest::where('user_id', auth()->id())->exists();
        if ($exists) {
            return back()->with('error', 'You have already sent a request.');
        }

        $session = str_replace('–', '-', $request->session);

        ProfileRequest::create([
            'user_id'        => auth()->id(),
            'status'         => 'pending',
            'student_name'   => $request->student_name,
            'father_name'    => $request->father_name,
            'roll_no'        => $request->roll_no,
            'department_id'  => $request->department_id,
            'semester'       => $request->semester,
            'session'        => $session, 
            'course'         => $request->course,
            'contact_number' => $request->contact_number,
        ]);

        return back()->with('success', 'Request sent successfully! Please wait for admin approval.');
    }

    public function leaveForm()
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'First, please set up your profile.');
        }

        LeaveRequest::where('student_id', $student->id)
            ->where('status', 'approved')
            ->where('student_seen', false)
            ->update(['student_seen' => true]);

        $leaves = LeaveRequest::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('students.leave', compact('leaves'));
    }

    public function submitLeave(Request $request)
    {
        $request->validate([
            'leave_date' => 'required|date',
            'reason'     => 'required|string', 
        ]);

        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return back()->with('error', 'Student profile not found.');
        }

        LeaveRequest::create([
            'student_id'  => $student->id,
            'leave_date'  => $request->leave_date,
            'reason'      => $request->reason,
            'status'      => 'pending'
        ]);

        return redirect()->back()->with('success', 'Leave request submitted.');
    }

    public function show(Student $student)
    {
        $student->load('department');

        $presentCount = Attendance::where('student_id', $student->id)->where('status', 'present')->count();
        $absentCount  = Attendance::where('student_id', $student->id)->where('status', 'absent')->count();
        $lateCount    = Attendance::where('student_id', $student->id)->where('status', 'late')->count();
        $leaveCount   = Attendance::where('student_id', $student->id)->where('status', 'leave')->count();

        return view('students.show', compact('student', 'presentCount', 'absentCount', 'lateCount', 'leaveCount'));
    }

    public function fixOldStudentLogins()
    {
        $students = Student::whereNotNull('roll_number')->get();
        $fixedCount = 0;

        foreach ($students as $student) {
            $expectedEmail = strtolower($student->roll_number) . '@gmail.com';

            if ($student->user) {
                $user = $student->user;

                if ($user->email !== $expectedEmail) {
                    $exists = User::where('email', $expectedEmail)->where('id', '!=', $user->id)->first();

                    if (!$exists) {
                        $user->email = $expectedEmail;
                        $user->password = Hash::make('password123');
                        $user->save();
                        $fixedCount++;
                    }
                }
            }
        }

        return "Fixed <b>$fixedCount</b> student accounts. Password set to: password123";
    }
}