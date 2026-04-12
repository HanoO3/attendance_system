@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Student Profile</h2>
            <p class="text-light opacity-75 mb-0">Detailed information and statistics</p>
        </div>
        <div>
            <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left: Profile Card -->
        <div class="col-lg-5 mb-4">
            <div class="card border-light shadow h-100">
                <div class="card-body text-center">
                    <div class="avatar-circle mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ strtoupper(substr($student->student_name ?? 'N', 0, 1)) }}
                    </div>
                    <h3 class="text-white mb-1">{{ $student->student_name }}</h3>
                    <p class="text-light opacity-75 mb-3">{{ $student->department->name ?? 'N/A' }}</p>
                    
                    <hr class="border-light">
                    
                    <div class="text-start">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-light opacity-50">Roll Number</td>
                                <td class="text-white text-end fw-bold">{{ $student->roll_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-light opacity-50">Father Name</td>
                                <td class="text-white text-end">{{ $student->father_name }}</td>
                            </tr>
                            <tr>
                                <td class="text-light opacity-50">Course</td>
                                <td class="text-white text-end">{{ $student->course }}</td>
                            </tr>
                            <tr>
                                <td class="text-light opacity-50">Semester</td>
                                <td class="text-white text-end">{{ $student->semester }}</td>
                            </tr>
                            <tr>
                                <td class="text-light opacity-50">Session</td>
                                <td class="text-white text-end">{{ $student->session }}</td>
                            </tr>
                            <tr>
                                <td class="text-light opacity-50">Contact</td>
                                <td class="text-white text-end">{{ $student->contact_number }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Attendance Stats -->
        <div class="col-lg-7 mb-4">
            <div class="card border-light shadow h-100">
                <div class="card-header">
                    <h5 class="mb-0">Attendance Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <!-- Present -->
                        <div class="col-6 mb-4">
                            <div class="p-3 rounded" style="background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.2);">
                                <h2 class="text-success mb-0">{{ $presentCount }}</h2>
                                <small class="text-light opacity-75">Present</small>
                            </div>
                        </div>
                        
                        <!-- Absent -->
                        <div class="col-6 mb-4">
                            <div class="p-3 rounded" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.2);">
                                <h2 class="text-danger mb-0">{{ $absentCount }}</h2>
                                <small class="text-light opacity-75">Absent</small>
                            </div>
                        </div>
                        
                        <!-- Late -->
                        <div class="col-6 mb-4">
                            <div class="p-3 rounded" style="background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.2);">
                                <h2 class="text-warning mb-0">{{ $lateCount }}</h2>
                                <small class="text-light opacity-75">Late</small>
                            </div>
                        </div>
                        
                        <!-- Leave -->
                        <div class="col-6 mb-4">
                            <div class="p-3 rounded" style="background: rgba(253, 126, 20, 0.1); border: 1px solid rgba(253, 126, 20, 0.2);">
                                <h2 style="color: #fd7e14;" class="mb-0">{{ $leaveCount }}</h2>
                                <small class="text-light opacity-75">Leave</small>
                            </div>
                        </div>
                    </div>

                    <hr class="border-light">

                    <div class="text-center">
                        <small class="text-light opacity-50 d-block mb-2">Quick Actions</small>
                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        background: linear-gradient(135deg, rgba(118, 75, 162, 0.6), rgba(255, 255, 255, 0.2));
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #fff;
    }
</style>
@endsection