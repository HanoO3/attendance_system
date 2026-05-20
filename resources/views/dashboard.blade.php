@extends('layouts.app')

@section('content')
<div class="pg-hdr">
    <h2>Dashboard Overview</h2>
    <p>Monitor your institution's attendance statistics at a glance</p>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <a href="{{ route('students.index') }}" class="stat-card">
            <div class="st-ico blue"><i class="fas fa-user-graduate"></i></div>
            <div>
                <div class="st-lbl">Total Students</div>
                <div class="st-val">{{ $studentsCount ?? 0 }}</div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-sm-6">
        <a href="{{ route('departments.index') }}" class="stat-card">
            <div class="st-ico violet"><i class="fas fa-building"></i></div>
            <div>
                <div class="st-lbl">Departments</div>
                <div class="st-val">{{ $departmentsCount ?? 0 }}</div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-sm-6">
        <a href="{{ route('teachers.index') }}" class="stat-card">
            <div class="st-ico amber"><i class="fas fa-chalkboard-teacher"></i></div>
            <div>
                <div class="st-lbl">Total Teachers</div>
                <div class="st-val">{{ $teachersCount ?? 0 }}</div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-sm-6">
        <a href="#" data-bs-toggle="modal" data-bs-target="#activeStudentsModal" class="stat-card">
            <div class="st-ico green"><i class="fas fa-user-check"></i></div>
            <div>
                <div class="st-lbl">Active Students</div>
                <div class="st-val">{{ $activeStudents ?? 0 }}</div>
            </div>
        </a>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-3">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <div class="hico"><i class="fas fa-chart-donut"></i></div>
                Attendance Overview
            </div>
            <div class="card-body" style="padding:20px;">
                <div id="attendanceChart" style="min-height:300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <div class="hico"><i class="fas fa-bolt"></i></div>
                Quick Actions
            </div>
            <div style="overflow:hidden;border-radius:0 0 12px 12px;">
                <a href="{{ route('students.create') }}" class="qa-item">
                    <div class="qa-ico" style="background:rgba(99,102,241,.1);color:#6366f1;"><i class="fas fa-user-plus"></i></div>
                    <div>
                        <div class="qa-lbl">Add New Student</div>
                        <div class="qa-sub">Register a student</div>
                    </div>
                </a>
                <a href="{{ route('admin.requests') }}" class="qa-item">
                    <div class="qa-ico" style="background:rgba(245,158,11,.1);color:#f59e0b;"><i class="fas fa-inbox"></i></div>
                    <div class="d-flex justify-content-between w-100 align-items-center">
                        <div>
                            <div class="qa-lbl">Profile Requests</div>
                            <div class="qa-sub">Review pending updates</div>
                        </div>
                        @if(isset($pendingProfileCount) && $pendingProfileCount > 0)
                            <span class="badge bg-danger">{{ $pendingProfileCount }}</span>
                        @endif
                    </div>
                </a>
                <a href="{{ route('admin.leaves') }}" class="qa-item">
                    <div class="qa-ico" style="background:rgba(6,182,212,.1);color:#06b6d4;"><i class="fas fa-calendar-check"></i></div>
                    <div class="d-flex justify-content-between w-100 align-items-center">
                        <div>
                            <div class="qa-lbl">Leave Approvals</div>
                            <div class="qa-sub">Process leave requests</div>
                        </div>
                        @if(isset($pendingLeavesCount) && $pendingLeavesCount > 0)
                            <span class="badge bg-danger">{{ $pendingLeavesCount }}</span>
                        @endif
                    </div>
                </a>
                <a href="{{ route('attendance.index') }}" class="qa-item">
                    <div class="qa-ico" style="background:rgba(16,185,129,.1);color:#10b981;"><i class="fas fa-clipboard-check"></i></div>
                    <div>
                        <div class="qa-lbl">Mark Attendance</div>
                        <div class="qa-sub">Take today's attendance</div>
                    </div>
                </a>
                <a href="{{ route('reports.index') }}" class="qa-item">
                    <div class="qa-ico" style="background:rgba(239,68,68,.1);color:#ef4444;"><i class="fas fa-file-pdf"></i></div>
                    <div>
                        <div class="qa-lbl">Generate Report</div>
                        <div class="qa-sub">Export attendance PDF</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Active Students Modal -->
<div class="modal fade" id="activeStudentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight:700;font-size:.95rem;">
                    <i class="fas fa-user-check text-success me-2"></i> Active Students
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th><th>Name</th><th>Roll No</th>
                                <th>Department</th><th>Semester</th><th>Session</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeStudentsData as $s)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="font-weight:600;">{{ $s->student_name }}</td>
                                <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $s->roll_number }}</span></td>
                                <td>{{ $s->department->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-info bg-opacity-10 text-info">{{ $s->semester }}</span></td>
                                <td style="color:#64748b;">{{ $s->session ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5" style="color:#94a3b8;">
                                    <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                    No active students found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var options = {
        series: [{{ $presentCount ?? 0 }}, {{ $absentCount ?? 0 }}, {{ $lateCount ?? 0 }}, {{ $leaveCount ?? 0 }}],
        chart: { type: 'donut', height: 300, foreColor: '#6b7280', toolbar: { show: false } },
        plotOptions: {
            pie: { donut: { size: '68%', labels: {
                show: true,
                name: { show: true, fontSize: '14px', color: '#111827' },
                value: { show: true, fontSize: '22px', color: '#111827', fontWeight: '800',
                    fontFamily: 'Outfit, sans-serif' },
                total: { show: true, label: 'Total Records', color: '#6b7280',
                    fontFamily: 'Outfit, sans-serif',
                    formatter: w => w.globals.seriesTotals.reduce((a,b) => a+b, 0) }
            }}}
        },
        dataLabels: { enabled: false },
        labels: ['Present', 'Absent', 'Late', 'Leave'],
        colors: ['#10b981', '#ef4444', '#f59e0b', '#fd7e14'],
        stroke: { width: 0 },
        legend: { position: 'bottom', fontFamily: 'Outfit, sans-serif', fontSize: '13px', fontWeight: '600',
            markers: { size: 8, shape: 'circle' }, labels: { colors: '#6b7280' } }
    };
    new ApexCharts(document.querySelector("#attendanceChart"), options).render();
});
</script>
@endsection