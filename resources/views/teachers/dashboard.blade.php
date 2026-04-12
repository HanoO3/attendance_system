@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Teacher Dashboard</h2>
            <p class="text-light opacity-75 mb-0">Welcome back, {{ $teacher->name ?? 'Teacher' }}</p>
        </div>
    </div>

    <!-- Row 1: Profile & Quick Actions -->
    <div class="row mb-4">
        
        <!-- Profile Info Card -->
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card border-light shadow h-100">
                <div class="card-header py-3 border-light">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-id-badge me-2"></i>My Profile Info</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-light text-white">
                            Department
                            <span class="badge bg-primary bg-opacity-25 text-primary">{{ $teacher->department->name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-light text-white">
                            Default Semester
                            <span class="badge bg-info bg-opacity-25 text-info">{{ $teacher->semester }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-light text-white">
                            Default Session
                            <span class="badge bg-light text-dark">{{ $teacher->session ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card (Styled Like Image) -->
        <div class="col-lg-6">
            <div class="card border-light shadow h-100">
                <div class="card-header py-3 border-light">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="quickActionsList">
                        
                        <!-- Notification Action -->
                        <a href="{{ route('teacher.notifications') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 border-light">
                            <div class="me-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-bell text-white"></i>
                                </div>
                            </div>
                            <div>
                                <span class="font-weight-bold text-white">View Notifications</span>
                            </div>
                        </a>

                        <!-- Report Action -->
                        <a href="{{ route('reports.index') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 border-light">
                            <div class="me-3">
                                <div class="icon-circle bg-danger">
                                    <i class="fas fa-file-pdf text-white"></i>
                                </div>
                            </div>
                            <div>
                                <span class="font-weight-bold text-white">Generate Report</span>
                            </div>
                        </a>

                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Row 2: Assigned Subjects List -->
    <div class="card border-light shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center border-light">
            <h5 class="mb-0"><i class="fas fa-book me-2"></i> My Assigned Subjects</h5>
        </div>
        <div class="card-body p-0">
            @if(auth()->user()->assignedSubjects->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-transparent">
                        <tr>
                            <th class="border-0 text-light opacity-75 ps-4">Subject Name</th>
                            <th class="border-0 text-light opacity-75">Code</th>
                            <th class="border-0 text-light opacity-75">Department</th>
                            <th class="border-0 text-light opacity-75">Semester</th>
                            <th class="border-0 text-light opacity-75">Session</th>
                            <th class="border-0 text-light opacity-75 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(auth()->user()->assignedSubjects as $subject)
                        <tr>
                            <td class="ps-4 text-white fw-bold">{{ $subject->name }}</td>
                            <td><span class="badge bg-info bg-opacity-25 text-info">{{ $subject->code }}</span></td>
                            <td class="text-white">{{ $subject->department->name ?? 'N/A' }}</td>
                            <td class="text-white">{{ $subject->semester }}</td>
                            <td class="text-white">{{ $subject->pivot->session }}</td>
                            <td class="text-center">
                                <a href="{{ route('attendance.index', ['subject_id' => $subject->id, 'session' => $subject->pivot->session]) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-clipboard-check me-1"></i> Mark Attendance
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-chalkboard-teacher fa-3x mb-3 opacity-50"></i>
                <h5>No Subjects Assigned</h5>
                <p class="text-light opacity-50">Contact admin to get subjects assigned.</p>
            </div>
            @endif
        </div>
    </div>

</div>

<style>
    /* Icon Circle Styling (From Image) */
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    /* List Item Styling */
    #quickActionsList .list-group-item {
        background-color: transparent;
        border-color: rgba(255,255,255,0.1);
        transition: all 0.2s;
    }
    #quickActionsList .list-group-item:hover {
        background-color: rgba(255,255,255,0.1);
        transform: translateX(5px);
    }
</style>
@endsection