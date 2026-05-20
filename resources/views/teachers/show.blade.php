@extends('layouts.app')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Teacher Profile</h1>
        <p style="font-size:.82rem;color:#6b7280;margin:0;">Details and assigned students</p>
    </div>
    <a href="{{ route('teachers.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
</div>

<div class="row g-4 mb-4">
    <!-- Personal Info -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <div class="hico"><i class="fas fa-user-tie"></i></div>
                Personal Information
            </div>
            <div class="card-body p-0">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td style="color:#9ca3af;font-weight:600;padding:14px 18px;width:40%;">Full Name</td>
                        <td style="color:#111827;font-weight:700;padding:14px 18px;">{{ $teacher->name }}</td>
                    </tr>
                    <tr style="border-top:1px solid #e5e7eb;">
                        <td style="color:#9ca3af;font-weight:600;padding:14px 18px;">Email</td>
                        <td style="color:#374151;padding:14px 18px;">{{ $teacher->user->email ?? 'N/A' }}</td>
                    </tr>
                    <tr style="border-top:1px solid #e5e7eb;">
                        <td style="color:#9ca3af;font-weight:600;padding:14px 18px;">Contact</td>
                        <td style="color:#374151;padding:14px 18px;">{{ $teacher->contact }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Assigned Info -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <div class="hico"><i class="fas fa-chalkboard"></i></div>
                Assigned Info
            </div>
            <div class="card-body p-0">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td style="color:#9ca3af;font-weight:600;padding:14px 18px;width:40%;">Department</td>
                        <td style="padding:14px 18px;">
                            <span class="badge bg-primary">{{ $teacher->department->name ?? 'N/A' }}</span>
                        </td>
                    </tr>
                    <tr style="border-top:1px solid #e5e7eb;">
                        <td style="color:#9ca3af;font-weight:600;padding:14px 18px;">Semester</td>
                        <td style="padding:14px 18px;">
                            <span class="badge bg-info text-dark">{{ $teacher->semester }}</span>
                        </td>
                    </tr>
                    <tr style="border-top:1px solid #e5e7eb;">
                        <td style="color:#9ca3af;font-weight:600;padding:14px 18px;">Session</td>
                        <td style="padding:14px 18px;">
                            <span class="badge bg-secondary">{{ $teacher->session }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Students Table -->
@if($students->count() > 0)
<div class="card">
    <div class="card-header">
        <div class="hico"><i class="fas fa-users"></i></div>
        Assigned Students ({{ $students->count() }})
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th>Session</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td style="color:#9ca3af;">{{ $loop->iteration }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $student->roll_number }}</span></td>
                        <td style="font-weight:600;color:#111827;">{{ $student->student_name }}</td>
                        <td style="color:#6b7280;">{{ $student->session }}</td>
                        <td style="color:#6b7280;">{{ $student->contact_number }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="card">
    <div class="card-body text-center py-5" style="color:#9ca3af;">
        <i class="fas fa-users-slash fa-3x mb-3 d-block opacity-50"></i>
        <h5>No Students Assigned</h5>
        <p>No students found for this semester and department.</p>
    </div>
</div>
@endif
@endsection