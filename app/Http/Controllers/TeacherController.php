<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Department;
use App\Models\Notification; 
use App\Models\Student; 
use App\Models\ActivityLog; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    
    public function index()
    {
        $teachers = Teacher::with('user', 'department')->get();
        $departments = Department::all();

        $sessions = Student::whereNotNull('session')
                    ->distinct()
                    ->pluck('session')
                    ->sort()
                    ->values();

        return view('teachers.index', compact('teachers', 'departments', 'sessions'));
    }

    
    public function create()
    {
        $departments = Department::all();
        $semesters = ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'];

        $sessions = Student::whereNotNull('session')
                    ->distinct()
                    ->pluck('session')
                    ->sort()
                    ->values();

        return view('teachers.create', compact('departments', 'semesters', 'sessions'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'contact'       => 'required|digits:11|starts_with:03',
            'department_id' => 'required|exists:departments,id',
            'semester'      => 'required|in:1st,2nd,3rd,4th,5th,6th,7th,8th',
            'session'       => 'required|string',
        ], [
            'contact.required' => 'Contact number is required.',
            'contact.digits' => 'Contact number must be exactly 11 digits.',
            'contact.starts_with' => 'Contact number must start with 03 (e.g., 03001234567).',
        ]);

        $department = Department::find($request->department_id);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make('password@123'),
            'role'     => 'teacher',
        ]);

        $teacher = Teacher::create([
            'user_id'       => $user->id,
            'name'          => $request->name,
            'contact'       => $request->contact,
            'department_id' => $request->department_id,
            'semester'      => $request->semester,
            'session'       => $request->session,
        ]);

        ActivityLog::create([
            'log_name'     => 'Teacher Created',
            'description'  => 'Added a new teacher: ' . $request->name,
            'subject_id'   => $teacher->id,
            'subject_type' => Teacher::class,
            'causer_id'    => auth()->id(),
            'causer_type'  => User::class,
            'properties'   => json_encode([
                'name'        => $request->name,
                'email'       => $request->email,
                'contact'     => $request->contact,
                'department'  => $department->name ?? 'N/A',
                'semester'    => $request->semester,
                'session'     => $request->session,
            ])
        ]);

        return redirect()->route('teachers.index')
                         ->with('success', 'Teacher added successfully.');
    }

    
    public function edit($id)
    {
        $teacher = Teacher::find($id);
        
        if (!$teacher) {
            return redirect()->route('teachers.index')->with('error', 'Teacher not found.');
        }

        $departments = Department::all();
        $semesters = ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'];

        $sessions = Student::whereNotNull('session')
                    ->distinct()
                    ->pluck('session')
                    ->sort()
                    ->values();

        return view('teachers.edit', compact('teacher', 'departments', 'semesters', 'sessions'));
    }

   
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'contact' => 'required|digits:11|starts_with:03',
            'department_id' => 'required',
            'semester' => 'required',
            'session' => 'required',
        ], [
            'contact.required' => 'Contact number is required.',
            'contact.digits' => 'Contact number must be exactly 11 digits.',
            'contact.starts_with' => 'Contact number must start with 03.',
        ]);

        $teacher = Teacher::find($id);
        
        if (!$teacher) {
            return back()->with('error', 'Teacher not found.');
        }

        $oldDepartment = $teacher->department;
        $oldData = [
            'name'        => $teacher->name,
            'email'       => $teacher->user->email ?? 'N/A', 
            'contact'     => $teacher->contact,
            'department'  => $oldDepartment->name ?? 'N/A',
            'semester'    => $teacher->semester,
            'session'     => $teacher->session,
        ];

        $teacher->update([
            'name' => $request->name,
            'contact' => $request->contact,
            'department_id' => $request->department_id,
            'semester' => $request->semester,
            'session' => $request->session,
        ]);

        if ($teacher->user) {
            $teacher->user->update(['name' => $request->name]);
        }

        $newDepartment = Department::find($request->department_id);
        $newData = [
            'name'        => $teacher->name,
            'email'       => $teacher->user->email ?? 'N/A', 
            'contact'     => $teacher->contact,
            'department'  => $newDepartment->name ?? 'N/A',
            'semester'    => $teacher->semester,
            'session'     => $teacher->session,
        ];

        ActivityLog::create([
            'log_name'     => 'Teacher Updated',
            'description'  => 'Updated teacher details: ' . $teacher->name,
            'subject_id'   => $teacher->id,
            'subject_type' => Teacher::class,
            'causer_id'    => auth()->id(),
            'causer_type'  => User::class,
            'properties'   => json_encode([
                'old' => $oldData,
                'new' => $newData
            ])
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

  
    public function destroy($id)
    {
        $teacher = Teacher::find($id);
        
        if ($teacher) {
            $department = $teacher->department;
            $email = $teacher->user->email ?? 'N/A';

            ActivityLog::create([
                'log_name'     => 'Teacher Deleted',
                'description'  => 'Deleted teacher: ' . $teacher->name,
                'subject_id'   => $teacher->id,
                'subject_type' => Teacher::class,
                'causer_id'    => auth()->id(),
                'causer_type'  => User::class,
                'properties'   => json_encode([
                    'name'        => $teacher->name,
                    'email'       => $email,
                    'contact'     => $teacher->contact,
                    'department'  => $department->name ?? 'N/A',
                    'semester'    => $teacher->semester,
                    'session'     => $teacher->session,
                ])
            ]);

            if ($teacher->user) {
                $teacher->user->delete(); 
            }
            $teacher->delete(); 
        }

        return back()->with('success', 'Teacher deleted.');
    }

    
public function dashboard()
{
    $teacher = Teacher::where('user_id', auth()->id())->first();

    if (!$teacher || !$teacher->department_id) {

        $existingRequest = \App\Models\ProfileRequest::where('user_id', auth()->id())
                            ->where('type', 'teacher')
                            ->first();

        $departments = \App\Models\Department::all();
        $semesters   = ['1st','2nd','3rd','4th','5th','6th','7th','8th'];

        $sessions = \App\Models\Student::whereNotNull('session')
                    ->distinct()->pluck('session')->sort()->values();

        return view('teachers.setup', compact(
            'existingRequest', 'departments', 'semesters', 'sessions'
        ));
    }

    $students = Student::where('department_id', $teacher->department_id)
                ->where('semester', $teacher->semester)
                ->get();

    return view('teachers.dashboard', compact('teacher', 'students'));
}

public function sendProfileRequest(Request $request)
{
    $request->validate([
        'teacher_name'  => 'required|string|max:255',
        'contact'       => 'required|digits:11|starts_with:03',
        'department_id' => 'required|exists:departments,id',
        'semester'      => 'required',
        'session'       => 'required|string',
    ], [
        'contact.digits'      => 'Contact number must be exactly 11 digits.',
        'contact.starts_with' => 'Contact number must start with 03.',
    ]);

    $already = \App\Models\ProfileRequest::where('user_id', auth()->id())
                ->where('type', 'teacher')
                ->exists();

    if ($already) {
        return back()->with('info', 'You have already submitted a request. Please wait for admin approval.');
    }

    \App\Models\ProfileRequest::create([
        'user_id'       => auth()->id(),
        'type'          => 'teacher',
        'status'        => 'pending',
        'teacher_name'  => $request->teacher_name,
        'contact'       => $request->contact,
        'department_id' => $request->department_id,
        'semester'      => $request->semester,
        'session'       => $request->session,
    ]);

    return back()->with('success', 'Request sent! Admin will assign your details soon.');
}

    public function show(Teacher $teacher)
    {
        $teacher->load('user', 'department');

        $students = Student::where('department_id', $teacher->department_id)
                    ->where('semester', $teacher->semester)
                    ->get();

        return view('teachers.show', compact('teacher', 'students'));
    }


    public function notifications()
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();

        Notification::where('user_id', auth()->id())->update(['is_read' => true]);

        $notifications = Notification::where('user_id', auth()->id())
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);

        return view('teachers.notifications', compact('notifications', 'teacher'));
    }
}