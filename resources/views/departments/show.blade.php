@extends('layouts.app')
@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">{{ $department->name }}</h1>
        <p style="font-size:.82rem;color:#6b7280;margin:0;">Department details and enrolled students</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-outline-warning btn-sm">
            <i class="fas fa-pen me-1"></i> Edit
        </a>
        <a href="{{ route('departments.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="st-ico blue"><i class="fas fa-building"></i></div>
            <div>
                <div class="st-lbl">Department</div>
                <div style="font-size:1rem;font-weight:800;color:#111827;">{{ $department->name }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="st-ico green"><i class="fas fa-user-graduate"></i></div>
            <div>
                <div class="st-lbl">Total Students</div>
                <div class="st-val">{{ $department->students->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="st-ico violet"><i class="fas fa-id-badge"></i></div>
            <div>
                <div class="st-lbl">Dept Code</div>
                <div style="font-size:1rem;font-weight:800;color:#111827;">
                    {{ \App\Models\Department::generateCourseCode($department->name) }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Students Table -->
<div class="card">
    <div class="card-header">
        <div class="hico"><i class="fas fa-users"></i></div>
        Enrolled Students ({{ $department->students->count() }})
    </div>
    <div class="card-body p-0">
        @if($department->students->count() > 0)
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th>Father Name</th>
                        <th>Semester</th>
                        <th>Session</th>
                        <th>Contact</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($department->students as $student)
                    <tr>
                        <td style="color:#9ca3af;">{{ $loop->iteration }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $student->roll_number }}</span></td>
                        <td style="font-weight:700;color:#111827;">{{ $student->student_name }}</td>
                        <td style="color:#6b7280;">{{ $student->father_name }}</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info fw-bold">{{ $student->semester }}</span></td>
                        <td style="color:#6b7280;">{{ $student->session }}</td>
                        <td style="color:#6b7280;">{{ $student->contact_number }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5" style="color:#9ca3af;">
            <i class="fas fa-user-graduate fa-3x mb-3 d-block opacity-50"></i>
            <h5>No Students Enrolled</h5>
            <p>No students found in this department yet.</p>
            <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-user-plus me-1"></i> Add Student
            </a>
        </div>
        @endif
    </div>
</div>

@endsection