@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Activity Details</h2>
            <p class="text-light opacity-75 mb-0">Full log information</p>
        </div>
        <div>
            <form action="{{ route('activity-logs.destroy', $log->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm me-2">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </form>
            <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left: Basic Info -->
        <div class="col-md-4">
            <div class="card border-light shadow h-100">
                <div class="card-header">Log Info</div>
                <div class="card-body">
                    <p><strong class="text-light opacity-75">Date & Time:</strong><br> {{ $log->created_at->format('d M Y - h:i A') }}</p>
                    <p><strong class="text-light opacity-75">Performed By:</strong><br> 
                        <span class="badge bg-primary">{{ $log->causer->name ?? 'System' }}</span>
                    </p>
                    <p>
                        <strong class="text-light opacity-75">Type:</strong><br> 
                        <span class="badge 
                            @if(str_contains($log->log_name, 'Created')) bg-success
                            @elseif(str_contains($log->log_name, 'Updated')) bg-warning text-dark
                            @elseif(str_contains($log->log_name, 'Deleted')) bg-danger
                            @elseif(str_contains($log->log_name, 'Attendance')) bg-info
                            @else bg-secondary @endif">
                            {{ $log->log_name }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Right: Details -->
        <div class="col-md-8">
            <div class="card border-light shadow h-100">
                <div class="card-header">Details</div>
                <div class="card-body">
                    @php
                        // Decode properties safely
                        $data = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
                    @endphp

                    <!-- CASE 1: IF UPDATED -->
                    @if(isset($data['old']) && isset($data['new']))
                        <h4 class="text-white mb-3"><i class="fas fa-exchange-alt me-2"></i>Update Comparison</h4>
                        <hr class="border-light">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="p-3 rounded h-100" style="background: rgba(255, 0, 0, 0.1); border: 1px solid rgba(255,255,255,0.1);">
                                    <h5 class="text-danger mb-3"><i class="fas fa-history me-2"></i>Old Data</h5>
                                    <table class="table table-borderless table-sm mb-0">
                                        @foreach($data['old'] as $key => $val)
                                            <tr><td class="text-light opacity-50 text-uppercase small" width="40%">{{ $key }}</td><td class="text-white">{{ $val }}</td></tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded h-100" style="background: rgba(0, 255, 0, 0.1); border: 1px solid rgba(255,255,255,0.1);">
                                    <h5 class="text-success mb-3"><i class="fas fa-check-circle me-2"></i>New Data</h5>
                                    <table class="table table-borderless table-sm mb-0">
                                        @foreach($data['new'] as $key => $val)
                                            <tr><td class="text-light opacity-50 text-uppercase small" width="40%">{{ $key }}</td><td class="text-white">{{ $val }}</td></tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>

                    <!-- CASE 2: ATTENDANCE MARKED (UPDATED LOGIC) -->
                    @elseif(str_contains($log->log_name, 'Attendance') && $data)
                        <h4 class="text-white mb-3">Attendance Summary</h4>
                        <hr class="border-light">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <th class="text-light opacity-75">Date</th>
                                        <td class="text-white">{{ $data['date'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-light opacity-75">Subject</th>
                                        <td class="text-white">{{ $data['subject'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-light opacity-75">Department</th>
                                        <td class="text-white">{{ $data['department'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-light opacity-75">Semester</th>
                                        <td class="text-white">{{ $data['semester'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-light opacity-75">Session</th>
                                        <td class="text-white">{{ $data['session'] ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-around text-center">
                                    <div>
                                        <!-- FIXED: Direct key access -->
                                        <h3 class="text-success">{{ $data['present'] ?? 0 }}</h3>
                                        <small class="text-light">Present</small>
                                    </div>
                                    <div>
                                        <h3 class="text-danger">{{ $data['absent'] ?? 0 }}</h3>
                                        <small class="text-light">Absent</small>
                                    </div>
                                    <div>
                                        <h3 class="text-warning">{{ $data['late'] ?? 0 }}</h3>
                                        <small class="text-light">Late</small>
                                    </div>
                                    <div>
                                        <h3 style="color: #fd7e14;">{{ $data['leave'] ?? 0 }}</h3>
                                        <small class="text-light">Leave</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lists Section -->
                        @if(!empty($data['present_list']) || !empty($data['absent_list']) || !empty($data['late_list']))
                        <div class="mt-4">
                            <h6 class="text-uppercase text-light opacity-50 small">Student Lists</h6>
                            <hr class="border-light">
                            
                            @if(!empty($data['present_list']))
                                <h6 class="text-success mb-2"><i class="fas fa-user-check me-2"></i>Present Students:</h6>
                                <ul class="list-group list-group-flush mb-3" style="max-height: 150px; overflow-y: auto; border: 1px solid rgba(255,255,255,0.1); border-radius: 5px;">
                                    @foreach($data['present_list'] as $name)
                                        <li class="list-group-item bg-transparent text-white border-light py-1 small">{{ $name }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if(!empty($data['late_list']))
                                <h6 class="text-warning mb-2"><i class="fas fa-user-clock me-2"></i>Late Students:</h6>
                                <ul class="list-group list-group-flush mb-3" style="max-height: 150px; overflow-y: auto; border: 1px solid rgba(255,255,255,0.1); border-radius: 5px;">
                                    @foreach($data['late_list'] as $name)
                                        <li class="list-group-item bg-transparent text-white border-light py-1 small">{{ $name }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if(!empty($data['leave_list']))
                                <h6 class="mb-2" style="color: #fd7e14;"><i class="fas fa-user-times me-2"></i>On Leave:</h6>
                                <ul class="list-group list-group-flush mb-3" style="max-height: 150px; overflow-y: auto; border: 1px solid rgba(255,255,255,0.1); border-radius: 5px;">
                                    @foreach($data['leave_list'] as $name)
                                        <li class="list-group-item bg-transparent text-white border-light py-1 small">{{ $name }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if(!empty($data['absent_list']))
                                <h6 class="text-danger mb-2"><i class="fas fa-user-times me-2"></i>Absent Students:</h6>
                                <ul class="list-group list-group-flush" style="max-height: 150px; overflow-y: auto; border: 1px solid rgba(255,255,255,0.1); border-radius: 5px;">
                                    @foreach($data['absent_list'] as $name)
                                        <li class="list-group-item bg-transparent text-white border-light py-1 small">{{ $name }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        @endif

                    <!-- CASE 3: STUDENT -->
                    @elseif(str_contains($log->log_name, 'Student') && $data)
                        <h4 class="text-white mb-3">Student Information</h4>
                        <hr class="border-light">
                        <table class="table table-borderless">
                            <tr><th class="text-light opacity-75" width="40%">Student Name</th><td class="text-white fw-bold">{{ $data['student_name'] ?? 'N/A' }}</td></tr>
                            <tr><th class="text-light opacity-75">Roll Number</th><td class="text-white">{{ $data['roll_number'] ?? 'N/A' }}</td></tr>
                            <tr><th class="text-light opacity-75">Father Name</th><td class="text-white">{{ $data['father_name'] ?? 'N/A' }}</td></tr>
                            <tr><th class="text-light opacity-75">Department</th><td class="text-white">{{ $data['department'] ?? 'N/A' }}</td></tr>
                            <tr><th class="text-light opacity-75">Semester</th><td class="text-white">{{ $data['semester'] ?? 'N/A' }}</td></tr>
                            <tr><th class="text-light opacity-75">Session</th><td class="text-white">{{ $data['session'] ?? 'N/A' }}</td></tr>
                            <tr><th class="text-light opacity-75">Contact</th><td class="text-white">{{ $data['contact_number'] ?? 'N/A' }}</td></tr>
                        </table>

                    <!-- CASE 4: TEACHER -->
                    @elseif(str_contains($log->log_name, 'Teacher') && $data)
                         <h4 class="text-white mb-3">Teacher Information</h4>
                         <hr class="border-light">
                         <table class="table table-borderless">
                            <tr><th class="text-light opacity-75" width="40%">Name</th><td class="text-white fw-bold">{{ $data['name'] ?? 'N/A' }}</td></tr>
                            <tr><th class="text-light opacity-75">Email</th><td class="text-white">{{ $data['email'] ?? 'N/A' }}</td></tr>
                            <tr><th class="text-light opacity-75">Contact</th><td class="text-white">{{ $data['contact'] ?? 'N/A' }}</td></tr>
                            <tr><th class="text-light opacity-75">Department</th><td class="text-white">{{ $data['department'] ?? 'N/A' }}</td></tr>
                            <tr><th class="text-light opacity-75">Semester</th><td class="text-white">{{ $data['semester'] ?? 'N/A' }}</td></tr>
                            <tr><th class="text-light opacity-75">Session</th><td class="text-white">{{ $data['session'] ?? 'N/A' }}</td></tr>
                        </table>

                    <!-- CASE 5: NOTIFICATION -->
                    @elseif($log->log_name == 'Notification' && $data)
                        <div class="mb-3">
                            <label class="small text-uppercase text-light opacity-75">Title</label>
                            <h4 class="text-white mb-0">{{ $data['title'] ?? 'N/A' }}</h4>
                        </div>
                        <hr class="border-light">
                        <div class="mb-3">
                            <label class="small text-uppercase text-light opacity-75">Message</label>
                            <div class="p-3 rounded" style="background: rgba(0,0,0,0.3); white-space: pre-wrap;">{{ $data['message'] ?? 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="small text-uppercase text-light opacity-75">Sent To</label>
                                <h5>
                                    @if(($data['sent_to'] ?? '') == 'all')
                                        <span class="badge bg-success fs-6">All (Teachers & Students)</span>
                                    @elseif(($data['sent_to'] ?? '') == 'all_students')
                                        <span class="badge bg-info fs-6">All Students</span>
                                    @elseif(($data['sent_to'] ?? '') == 'all_teachers')
                                        <span class="badge bg-warning text-dark fs-6">All Teachers</span>
                                    @else
                                        <span class="badge bg-secondary fs-6">{{ $data['sent_to'] ?? 'N/A' }}</span>
                                    @endif
                                </h5>
                            </div>
                            <div class="col-6">
                                <label class="small text-uppercase text-light opacity-75">Recipients</label>
                                <h3 class="text-white">{{ $data['total_sent'] ?? 0 }}</h3>
                            </div>
                        </div>

                    <!-- DEFAULT -->
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Data Missing:</strong> 
                            Detailed properties not recorded for this log.
                            <hr>
                            <p class="mb-0 small">Description: {{ $log->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection