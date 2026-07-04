<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    
    public function index()
    {
        $departments = Department::withCount('students')->latest()->get();

        return view('departments.index', compact('departments'));
    }

   
    public function create()
    {
        return view('departments.create');
    }

  
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ]);

        Department::create([
            'name' => $request->name
        ]);

        return redirect()->route('departments.index')
                         ->with('success', 'Department created successfully.');
    }

    
    public function show(Department $department)
    {
        $department->load(['students' => function($query) {
            $query->with('user')->latest();
        }]);

        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

  
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
        ]);

        $department->update([
            'name' => $request->name
        ]);

        return redirect()->route('departments.index')
                         ->with('success', 'Department updated successfully.');
    }

 
    public function destroy(Department $department)
    {
        if ($department->students()->count() > 0) {
            return back()->with('error', 'Cannot delete department. It has students assigned to it.');
        }

        $department->delete();

        return redirect()->route('departments.index')
                         ->with('success', 'Department deleted successfully.');
    }

    
    public function getCourses($id)
    {
        $dept = Department::find($id);
        
        if(!$dept) {
            return response()->json([]);
        }

        $code = \App\Models\Department::generateCourseCode($dept->name); 
        
        return response()->json([
            ['id' => 1, 'code' => $code]
        ]);
    }
}