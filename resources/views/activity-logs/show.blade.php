@extends('layouts.app')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Activity Details</h1>
        <p style="font-size:.82rem;color:#6b7280;margin:0;">Full log information</p>
    </div>
    <div class="d-flex gap-2">
        <form action="{{ route('activity-logs.destroy', $log->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this log?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-trash me-1"></i> Delete
            </button>
        </form>
        <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-3">
    <!-- Left: Basic Info -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <div class="hico"><i class="fas fa-info-circle"></i></div>
                Log Info
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:5px;">Date &amp; Time</div>
                    <div style="font-weight:600;color:#111827;">{{ $log->created_at->format('d M Y - h:i A') }}</div>
                </div>
                <div class="mb-3">
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:5px;">Performed By</div>
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $log->causer->name ?? 'System' }}</span>
                </div>
                <div class="mb-3">
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:5px;">Log Type</div>
                    <span class="badge fw-semibold
                        @if(str_contains($log->log_name, 'Created')) bg-success
                        @elseif(str_contains($log->log_name, 'Updated')) bg-warning text-dark
                        @elseif(str_contains($log->log_name, 'Deleted')) bg-danger
                        @elseif(str_contains($log->log_name, 'Attendance')) bg-info
                        @else bg-secondary @endif">
                        {{ $log->log_name }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Details -->
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header">
                <div class="hico"><i class="fas fa-list-alt"></i></div>
                Details
            </div>
            <div class="card-body p-4">
                @php
                    $data = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
                @endphp

                {{-- CASE 1: UPDATED - Old vs New --}}
                @if(isset($data['old']) && isset($data['new']))
                    <h6 style="font-weight:700;color:#111827;margin-bottom:14px;">
                        <i class="fas fa-exchange-alt me-2 text-warning"></i>Update Comparison
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background:#fef2f2;border:1.5px solid #fecaca;">
                                <h6 class="text-danger mb-3"><i class="fas fa-history me-1"></i> Old Data</h6>
                                <table class="table table-borderless table-sm mb-0">
                                    @foreach($data['old'] as $key => $val)
                                    <tr>
                                        <td style="color:#9ca3af;font-size:.75rem;text-transform:uppercase;font-weight:600;width:40%;">{{ $key }}</td>
                                        <td style="color:#111827;font-weight:500;">{{ $val }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background:#f0fdf4;border:1.5px solid #bbf7d0;">
                                <h6 class="text-success mb-3"><i class="fas fa-check-circle me-1"></i> New Data</h6>
                                <table class="table table-borderless table-sm mb-0">
                                    @foreach($data['new'] as $key => $val)
                                    <tr>
                                        <td style="color:#9ca3af;font-size:.75rem;text-transform:uppercase;font-weight:600;width:40%;">{{ $key }}</td>
                                        <td style="color:#111827;font-weight:500;">{{ $val }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>

                {{-- CASE 2: ATTENDANCE --}}
                @elseif(str_contains($log->log_name, 'Attendance') && $data)
                    <h6 style="font-weight:700;color:#111827;margin-bottom:14px;">
                        <i class="fas fa-clipboard-check me-2 text-info"></i>Attendance Summary
                    </h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td style="color:#9ca3af;font-weight:600;font-size:.82rem;">Date</td>
                                    <td style="color:#111827;font-weight:600;">{{ $data['date'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#9ca3af;font-weight:600;font-size:.82rem;">Subject</td>
                                    <td style="color:#111827;font-weight:600;">{{ $data['subject'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#9ca3af;font-weight:600;font-size:.82rem;">Department</td>
                                    <td style="color:#111827;font-weight:600;">{{ $data['department'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#9ca3af;font-weight:600;font-size:.82rem;">Semester</td>
                                    <td style="color:#111827;font-weight:600;">{{ $data['semester'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#9ca3af;font-weight:600;font-size:.82rem;">Session</td>
                                    <td style="color:#111827;font-weight:600;">{{ $data['session'] ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-around text-center p-3 rounded" style="background:#f8fafc;border:1.5px solid #e5e7eb;">
                                <div>
                                    <div style="font-size:1.6rem;font-weight:800;color:#10b981;">{{ $data['present'] ?? 0 }}</div>
                                    <div style="font-size:.72rem;font-weight:600;color:#6b7280;text-transform:uppercase;">Present</div>
                                </div>
                                <div>
                                    <div style="font-size:1.6rem;font-weight:800;color:#ef4444;">{{ $data['absent'] ?? 0 }}</div>
                                    <div style="font-size:.72rem;font-weight:600;color:#6b7280;text-transform:uppercase;">Absent</div>
                                </div>
                                <div>
                                    <div style="font-size:1.6rem;font-weight:800;color:#f59e0b;">{{ $data['late'] ?? 0 }}</div>
                                    <div style="font-size:.72rem;font-weight:600;color:#6b7280;text-transform:uppercase;">Late</div>
                                </div>
                                <div>
                                    <div style="font-size:1.6rem;font-weight:800;color:#fd7e14;">{{ $data['leave'] ?? 0 }}</div>
                                    <div style="font-size:.72rem;font-weight:600;color:#6b7280;text-transform:uppercase;">Leave</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!empty($data['present_list']) || !empty($data['absent_list']) || !empty($data['late_list']) || !empty($data['leave_list']))
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:10px;">Student Lists</div>
                    <div class="row g-2">
                        @if(!empty($data['present_list']))
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background:#f0fdf4;border:1.5px solid #bbf7d0;">
                                <div class="text-success fw-bold mb-2" style="font-size:.82rem;"><i class="fas fa-user-check me-1"></i> Present</div>
                                @foreach($data['present_list'] as $name)
                                    <div style="font-size:.82rem;color:#111827;padding:2px 0;border-bottom:1px solid #d1fae5;">{{ $name }}</div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @if(!empty($data['absent_list']))
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background:#fef2f2;border:1.5px solid #fecaca;">
                                <div class="text-danger fw-bold mb-2" style="font-size:.82rem;"><i class="fas fa-user-times me-1"></i> Absent</div>
                                @foreach($data['absent_list'] as $name)
                                    <div style="font-size:.82rem;color:#111827;padding:2px 0;border-bottom:1px solid #fee2e2;">{{ $name }}</div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @if(!empty($data['late_list']))
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background:#fffbeb;border:1.5px solid #fde68a;">
                                <div class="text-warning fw-bold mb-2" style="font-size:.82rem;"><i class="fas fa-user-clock me-1"></i> Late</div>
                                @foreach($data['late_list'] as $name)
                                    <div style="font-size:.82rem;color:#111827;padding:2px 0;border-bottom:1px solid #fef3c7;">{{ $name }}</div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @if(!empty($data['leave_list']))
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background:#fff7ed;border:1.5px solid #fed7aa;">
                                <div class="fw-bold mb-2" style="font-size:.82rem;color:#fd7e14;"><i class="fas fa-calendar-minus me-1"></i> Leave</div>
                                @foreach($data['leave_list'] as $name)
                                    <div style="font-size:.82rem;color:#111827;padding:2px 0;border-bottom:1px solid #ffedd5;">{{ $name }}</div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                {{-- CASE 3: STUDENT --}}
                @elseif(str_contains($log->log_name, 'Student') && $data)
                    <h6 style="font-weight:700;color:#111827;margin-bottom:14px;">
                        <i class="fas fa-user-graduate me-2 text-primary"></i>Student Information
                    </h6>
                    <table class="table table-borderless mb-0">
                        <tr><td style="color:#9ca3af;font-weight:600;width:35%;">Student Name</td><td style="color:#111827;font-weight:700;">{{ $data['student_name'] ?? 'N/A' }}</td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Roll Number</td><td style="color:#111827;">{{ $data['roll_number'] ?? 'N/A' }}</td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Father Name</td><td style="color:#111827;">{{ $data['father_name'] ?? 'N/A' }}</td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Department</td><td style="color:#111827;">{{ $data['department'] ?? 'N/A' }}</td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Semester</td><td style="color:#111827;">{{ $data['semester'] ?? 'N/A' }}</td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Session</td><td style="color:#111827;">{{ $data['session'] ?? 'N/A' }}</td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Contact</td><td style="color:#111827;">{{ $data['contact_number'] ?? 'N/A' }}</td></tr>
                    </table>

                {{-- CASE 4: TEACHER --}}
                @elseif(str_contains($log->log_name, 'Teacher') && $data)
                    <h6 style="font-weight:700;color:#111827;margin-bottom:14px;">
                        <i class="fas fa-chalkboard-teacher me-2 text-warning"></i>Teacher Information
                    </h6>
                    <table class="table table-borderless mb-0">
                        <tr><td style="color:#9ca3af;font-weight:600;width:35%;">Name</td><td style="color:#111827;font-weight:700;">{{ $data['name'] ?? 'N/A' }}</td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Email</td><td style="color:#111827;">{{ $data['email'] ?? 'N/A' }}</td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Contact</td><td style="color:#111827;">{{ $data['contact'] ?? 'N/A' }}</td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Department</td><td style="color:#111827;">{{ $data['department'] ?? 'N/A' }}</td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Semester</td><td style="color:#111827;">{{ $data['semester'] ?? 'N/A' }}</td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Session</td><td style="color:#111827;">{{ $data['session'] ?? 'N/A' }}</td></tr>
                    </table>

                {{-- CASE 5: NOTIFICATION --}}
                @elseif($log->log_name == 'Notification' && $data)
                    <h6 style="font-weight:700;color:#111827;margin-bottom:14px;">
                        <i class="fas fa-bell me-2 text-info"></i>Notification Details
                    </h6>
                    <div class="mb-3">
                        <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;color:#9ca3af;margin-bottom:4px;">Title</div>
                        <div style="font-size:1rem;font-weight:700;color:#111827;">{{ $data['title'] ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;color:#9ca3af;margin-bottom:4px;">Message</div>
                        <div class="p-3 rounded" style="background:#f8fafc;border:1.5px solid #e5e7eb;color:#374151;white-space:pre-wrap;font-size:.88rem;">{{ $data['message'] ?? 'N/A' }}</div>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;color:#9ca3af;margin-bottom:6px;">Sent To</div>
                            @if(($data['sent_to'] ?? '') == 'all')
                                <span class="badge bg-success">All (Teachers &amp; Students)</span>
                            @elseif(($data['sent_to'] ?? '') == 'all_students')
                                <span class="badge bg-info">All Students</span>
                            @elseif(($data['sent_to'] ?? '') == 'all_teachers')
                                <span class="badge bg-warning text-dark">All Teachers</span>
                            @else
                                <span class="badge bg-secondary">{{ $data['sent_to'] ?? 'N/A' }}</span>
                            @endif
                        </div>
                        <div class="col-6">
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;color:#9ca3af;margin-bottom:4px;">Total Recipients</div>
                            <div style="font-size:1.5rem;font-weight:800;color:#111827;">{{ $data['total_sent'] ?? 0 }}</div>
                        </div>
                    </div>

                {{-- DEFAULT --}}
                @else
                    <div class="alert alert-warning" style="border-radius:10px;border:none;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Data Missing:</strong> Detailed properties not recorded for this log.
                        <hr>
                        <p class="mb-0 small">Description: {{ $log->description }}</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection