<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('department', 'teachers')->latest()->get();
        $teachers = User::where('role', 'teacher')->get(['id', 'name']);
        
        return view('subjects.index', compact('subjects', 'teachers'));
    }

    public function create()
    {
        $departments = Department::all();
        $teachers = User::where('role', 'teacher')->get(['id', 'name']);
        
        return view('subjects.create', compact('departments', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:subjects,code',
            'department_id' => 'required',
            'semester' => 'required',
            'teacher_id' => 'nullable|exists:users,id',
            'session_assign' => 'required_with:teacher_id|string|nullable', 
        ]);

        $subject = Subject::create($request->only(['name', 'code', 'department_id', 'semester']));

        if ($request->teacher_id && $request->session_assign) {
            $session = str_replace('–', '-', $request->session_assign);
            $subject->teachers()->attach($request->teacher_id, ['session' => $session]);
        }

        return redirect()->route('subjects.index')->with('success', 'Subject Created.');
    }

    public function edit(Subject $subject)
    {
        $departments = Department::all();
        $teachers = User::where('role', 'teacher')->get(['id', 'name']);
        
        $subject->load('teachers');

        return view('subjects.edit', compact('subject', 'departments', 'teachers'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:subjects,code,'.$subject->id,
            'department_id' => 'required',
            'semester' => 'required',
        ]);

        $subject->update($request->all());
        return redirect()->route('subjects.index')->with('success', 'Subject Updated.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return back()->with('success', 'Subject Deleted.');
    }

    public function assignTeacher(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'session'    => 'required|string',
        ]);

        $subject = Subject::find($request->subject_id);

        $session = str_replace('–', '-', $request->session);

        $exists = $subject->teachers()
                          ->where('teacher_id', $request->teacher_id)
                          ->wherePivot('session', $session)
                          ->exists();

        if ($exists) {
            return back()->with('error', 'Teacher already assigned for this session.');
        }

        $subject->teachers()->attach($request->teacher_id, ['session' => $session]);

        return back()->with('success', 'Teacher Assigned Successfully.');
    }

    public function removeTeacher(Subject $subject, User $user)
    {
        $subject->teachers()->detach($user->id);
        return back()->with('success', 'Teacher Removed Successfully.');
    }

  
public function getByDeptSem($dept_id, $semester)
{
    $query = Subject::where('department_id', $dept_id)
                    ->matchSemester($semester); 

    if (auth()->user()->role === 'teacher') {
        $query->whereHas('teachers', function ($q) {
            $q->where('users.id', auth()->id());
        });
    }

    $subjects = $query->get(['id', 'name']);

    return response()->json($subjects);
}
}
