@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Teacher Profile</h2>
            <p class="text-light opacity-75 mb-0">Details and Assigned Students</p>
        </div>
        <div>
            <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        
        <!-- Teacher Info Card -->
        <div class="col-lg-6">
            <div class="card border-primary shadow h-100">
                <div class="card-header py-3 bg-primary bg-opacity-10">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-tie me-2"></i>Personal Information
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between bg-transparent border-light text-white">
                            <span>Full Name</span>
                            <strong>{{ $teacher->name }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between bg-transparent border-light text-white">
                            <span>Email</span>
                            <strong>{{ $teacher->user->email ?? 'N/A' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between bg-transparent border-light text-white">
                            <span>Contact</span>
                            <strong>{{ $teacher->contact }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Assigned Info Card -->
        <div class="col-lg-6">
            <div class="card border-primary shadow h-100">
                <div class="card-header py-3 bg-primary bg-opacity-10">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chalkboard me-2"></i>Assigned Info
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between bg-transparent border-light text-white">
                            <span>Department</span>
                            <span class="badge bg-primary">{{ $teacher->department->name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between bg-transparent border-light text-white">
                            <span>Semester</span>
                            <span class="badge bg-primary">{{ $teacher->semester }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between bg-transparent border-light text-white">
                            <span>Session</span>
                            <span class="badge bg-primary">{{ $teacher->session }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Students List -->
    @if($students->count() > 0)
    <div class="card border-primary shadow">
        <div class="card-header py-3 bg-primary bg-opacity-10">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-users me-2"></i>Assigned Students ({{ $students->count() }})
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-transparent">
                        <tr>
                            <th class="border-0 text-light opacity-75 ps-3">Sr#</th>
                            <th class="border-0 text-light opacity-75">Roll No</th>
                            <th class="border-0 text-light opacity-75">Name</th>
                            <th class="border-0 text-light opacity-75">Session</th>
                            <th class="border-0 text-light opacity-75">Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td class="ps-3">{{ $loop->iteration }}</td>
                            <td>{{ $student->roll_number }}</td>
                            <td>{{ $student->student_name }}</td>
                            <td>{{ $student->session }}</td>
                            <td>{{ $student->contact_number }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="card border-primary shadow">
        <div class="card-body text-center py-5">
            <i class="fas fa-users-slash fa-3x mb-3 opacity-50 text-primary"></i>
            <h5>No Students Assigned</h5>
            <p class="text-light opacity-50">No students found for this semester and department.</p>
        </div>
    </div>
    @endif

</div>

<style>
    .list-group-item {
        border-color: rgba(255,255,255,0.1) !important;
    }
</style>
@endsection