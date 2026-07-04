@extends('layouts.app')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Student Profile</h1>
        <p style="font-size:.82rem;color:#6b7280;margin:0;">Detailed information and statistics</p>
    </div>
    <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
</div>

<div class="row g-4">
    <!-- Left: Profile Info -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-body p-4">
                <!-- Avatar -->
                <div class="text-center mb-4">
                    <div style="width:70px;height:70px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:26px;font-weight:800;margin:0 auto 12px;">
                        {{ strtoupper(substr($student->student_name ?? 'S', 0, 1)) }}
                    </div>
                    <h4 style="font-weight:800;color:#111827;margin-bottom:3px;">{{ $student->student_name }}</h4>
                    <span style="font-size:.82rem;color:#6b7280;">{{ $student->department->name ?? 'N/A' }}</span>
                </div>
                <hr style="border-color:#e5e7eb;">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td style="color:#9ca3af;font-weight:600;width:45%;">Roll Number</td>
                        <td style="color:#111827;font-weight:700;">{{ $student->roll_number }}</td>
                    </tr>
                    <tr>
                        <td style="color:#9ca3af;font-weight:600;">Father Name</td>
                        <td style="color:#374151;">{{ $student->father_name }}</td>
                    </tr>
                    <tr>
                        <td style="color:#9ca3af;font-weight:600;">Course Code</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info fw-bold">{{ $student->course }}</span></td>
                    </tr>
                    <tr>
                        <td style="color:#9ca3af;font-weight:600;">Semester</td>
                        <td style="color:#374151;">{{ $student->semester }}</td>
                    </tr>
                    <tr>
                        <td style="color:#9ca3af;font-weight:600;">Session</td>
                        <td style="color:#374151;">{{ $student->session }}</td>
                    </tr>
                    <tr>
                        <td style="color:#9ca3af;font-weight:600;">Contact</td>
                        <td style="color:#374151;">{{ $student->contact_number }}</td>
                    </tr>
                </table>
                <hr style="border-color:#e5e7eb;">
                <div class="text-center">
                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Attendance Stats -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <div class="hico"><i class="fas fa-chart-pie"></i></div>
                Attendance Overview
            </div>
            <div class="card-body p-4">
                <div class="row g-3 text-center mb-3">
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:#f0fdf4;border:1.5px solid #bbf7d0;">
                            <div style="font-size:2rem;font-weight:900;color:#10b981;">{{ $presentCount }}</div>
                            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;color:#6b7280;margin-top:2px;">Present</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:#fef2f2;border:1.5px solid #fecaca;">
                            <div style="font-size:2rem;font-weight:900;color:#ef4444;">{{ $absentCount }}</div>
                            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;color:#6b7280;margin-top:2px;">Absent</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:#fffbeb;border:1.5px solid #fde68a;">
                            <div style="font-size:2rem;font-weight:900;color:#f59e0b;">{{ $lateCount }}</div>
                            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;color:#6b7280;margin-top:2px;">Late</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:#fff7ed;border:1.5px solid #fed7aa;">
                            <div style="font-size:2rem;font-weight:900;color:#fd7e14;">{{ $leaveCount }}</div>
                            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;color:#6b7280;margin-top:2px;">Leave</div>
                        </div>
                    </div>
                </div>
                @php
                    $total = $presentCount + $absentCount + $lateCount + $leaveCount;
                    $pct = $total > 0 ? round(($presentCount / $total) * 100) : 0;
                @endphp
                <div style="background:#f8fafc;border:1.5px solid #e5e7eb;border-radius:10px;padding:16px;text-align:center;">
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;color:#9ca3af;margin-bottom:6px;">Attendance Rate</div>
                    <div style="font-size:2rem;font-weight:900;color:{{ $pct >= 75 ? '#10b981' : '#ef4444' }};">{{ $pct }}%</div>
                    <div class="progress mt-2" style="height:8px;border-radius:10px;">
                        <div class="progress-bar {{ $pct >= 75 ? 'bg-success' : 'bg-danger' }}" style="width:{{ $pct }}%;border-radius:10px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection