@extends('layouts.app')
@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Teacher Dashboard</h1>
    <p style="font-size:.82rem;color:#6b7280;margin:0;">Welcome back, <strong>{{ $teacher->name ?? 'Teacher' }}</strong></p>
</div>

<div class="row g-4 mb-4">
    <!-- Profile Info -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <div class="hico"><i class="fas fa-id-badge"></i></div>
                My Profile Info
            </div>
            <div class="card-body p-0">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td style="color:#9ca3af;font-weight:600;padding:14px 18px;width:45%;">Department</td>
                        <td style="padding:14px 18px;">
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $teacher->department->name ?? 'N/A' }}</span>
                        </td>
                    </tr>
                    <tr style="border-top:1px solid #e5e7eb;">
                        <td style="color:#9ca3af;font-weight:600;padding:14px 18px;">Semester</td>
                        <td style="padding:14px 18px;">
                            <span class="badge bg-info bg-opacity-10 text-info fw-bold">{{ $teacher->semester }}</span>
                        </td>
                    </tr>
                    <tr style="border-top:1px solid #e5e7eb;">
                        <td style="color:#9ca3af;font-weight:600;padding:14px 18px;">Session</td>
                        <td style="padding:14px 18px;">
                            <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold">{{ $teacher->session ?? 'N/A' }}</span>
                        </td>
                    </tr>
                    <tr style="border-top:1px solid #e5e7eb;">
                        <td style="color:#9ca3af;font-weight:600;padding:14px 18px;">Contact</td>
                        <td style="color:#374151;font-weight:600;padding:14px 18px;">{{ $teacher->contact ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <div class="hico"><i class="fas fa-bolt"></i></div>
                Quick Actions
            </div>
            <div style="overflow:hidden;border-radius:0 0 12px 12px;">
                <a href="{{ route('attendance.index') }}" class="qa-item">
                    <div class="qa-ico" style="background:rgba(16,185,129,.1);color:#10b981;">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div>
                        <div class="qa-lbl">Mark Attendance</div>
                        <div class="qa-sub">Take today's class attendance</div>
                    </div>
                </a>
                <a href="{{ route('reports.index') }}" class="qa-item">
                    <div class="qa-ico" style="background:rgba(239,68,68,.1);color:#ef4444;">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div>
                        <div class="qa-lbl">Generate Report</div>
                        <div class="qa-sub">Export attendance PDF</div>
                    </div>
                </a>
                <a href="{{ route('teacher.notifications') }}" class="qa-item">
                    <div class="qa-ico" style="background:rgba(99,102,241,.1);color:#6366f1;">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div>
                        <div class="qa-lbl">Notifications</div>
                        <div class="qa-sub">View announcements</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Assigned Subjects -->
<div class="card">
    <div class="card-header">
        <div class="hico"><i class="fas fa-book-open"></i></div>
        My Assigned Subjects
    </div>
    <div class="card-body p-0">
        @if(auth()->user()->assignedSubjects->count() > 0)
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject Name</th>
                        <th>Code</th>
                        <th>Department</th>
                        <th>Semester</th>
                        <th>Session</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(auth()->user()->assignedSubjects as $subject)
                    <tr>
                        <td style="color:#9ca3af;">{{ $loop->iteration }}</td>
                        <td style="font-weight:700;color:#111827;">{{ $subject->name }}</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info fw-bold">{{ $subject->code }}</span></td>
                        <td style="color:#374151;">{{ $subject->department->name ?? 'N/A' }}</td>
                        <td style="color:#374151;">{{ $subject->semester }}</td>
                        <td style="color:#6b7280;">{{ $subject->pivot->session }}</td>
                        <td class="text-center">
                            <a href="{{ route('attendance.index', [
                                    'department_id' => $subject->department_id,
                                    'semester'      => $subject->semester,
                                    'session'       => $subject->pivot->session,
                                    'subject_id'    => $subject->id
                               ]) }}"
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
        <div class="text-center py-5" style="color:#9ca3af;">
            <i class="fas fa-chalkboard-teacher fa-3x mb-3 d-block opacity-50"></i>
            <h5>No Subjects Assigned</h5>
            <p>Contact admin to get subjects assigned.</p>
        </div>
        @endif
    </div>
</div>

@endsection