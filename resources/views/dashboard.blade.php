@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Dashboard Overview</h2>
            <p class="text-light opacity-75 mb-0">Monitor your system statistics</p>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row">
        
        <!-- Total Students Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('students.index') }}" class="text-decoration-none">
                <div class="card border-light shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-light text-uppercase mb-1">
                                    Total Students
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $studentsCount ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Departments Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('departments.index') }}" class="text-decoration-none">
                <div class="card border-light shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-light text-uppercase mb-1">
                                    Departments
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $departmentsCount ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-building fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Teachers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('teachers.index') }}" class="text-decoration-none">
                <div class="card border-light shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-light text-uppercase mb-1">
                                    Total Teachers
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $teachersCount ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Active Students Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="#" data-bs-toggle="modal" data-bs-target="#activeStudentsModal" class="text-decoration-none">
                <div class="card border-light shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-light text-uppercase mb-1">
                                    Active Students
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $activeStudents ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Attendance Chart Area -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card border-light shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-white">Attendance Overview</h6>
                </div>
                <div class="card-body">
                    <div id="attendanceChart" style="min-height: 300px;"></div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card border-light shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-white">Quick Actions</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="quickActionsList">
                        
                        <!-- Add Student -->
                        <a href="{{ route('students.create') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                            <div class="me-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-user-plus text-white"></i>
                                </div>
                            </div>
                            <div>
                                <span class="font-weight-bold text-white">Add New Student</span>
                            </div>
                        </a>

                        <!-- View Requests -->
                        <a href="{{ route('admin.requests') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                            <div class="me-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-inbox text-white"></i>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between w-100 me-2">
                                <span class="font-weight-bold text-white">View Requests</span>
                                @if(isset($pendingProfileCount) && $pendingProfileCount > 0)
                                    <span class="badge bg-danger">{{ $pendingProfileCount }}</span>
                                @endif
                            </div>
                        </a>

                        <!-- Leave Approvals -->
                        <a href="{{ route('admin.leaves') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                            <div class="me-3">
                                <div class="icon-circle bg-info">
                                    <i class="fas fa-calendar-check text-white"></i>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between w-100 me-2">
                                <span class="font-weight-bold text-white">Leave Approvals</span>
                                @if(isset($pendingLeavesCount) && $pendingLeavesCount > 0)
                                    <span class="badge bg-danger">{{ $pendingLeavesCount }}</span>
                                @endif
                            </div>
                        </a>

                        <!-- Mark Attendance -->
                        <a href="{{ route('attendance.index') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                            <div class="me-3">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-clipboard-check text-white"></i>
                                </div>
                            </div>
                            <div>
                                <span class="font-weight-bold text-white">Mark Attendance</span>
                            </div>
                        </a>

                        <!-- Generate Report -->
                        <a href="{{ route('reports.index') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3">
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
</div>

<!-- Active Students Modal (Theme Matched) -->
<div class="modal fade" id="activeStudentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-light shadow" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
            
            <div class="modal-header border-light">
                <h5 class="modal-title text-white">
                    <i class="fas fa-user-check text-info me-2"></i>Active Students List
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead style="background: rgba(0,0,0,0.2);">
                            <tr>
                                <th class="border-0 text-light opacity-75 ps-3">Sr#</th>
                                <th class="border-0 text-light opacity-75">Name</th>
                                <th class="border-0 text-light opacity-75">Roll No</th>
                                <th class="border-0 text-light opacity-75">Department</th>
                                <th class="border-0 text-light opacity-75">Semester</th>
                                <th class="border-0 text-light opacity-75">Session</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeStudentsData as $s)
                            <tr style="border-color: rgba(255,255,255,0.1);">
                                <td class="ps-3 text-white">{{ $loop->iteration }}</td>
                                <td class="text-white fw-bold">{{ $s->student_name }}</td>
                                <td class="text-white">{{ $s->roll_number }}</td>
                                <td class="text-white">{{ $s->department->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info bg-opacity-25 text-info">{{ $s->semester }}</span>
                                </td>
                                <td class="text-white opacity-75">{{ $s->session ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
                                    <h5 class="text-white">No Active Students</h5>
                                    <p class="text-light opacity-50">No students with attendance records found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="modal-footer border-light">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
            </div>
            
        </div>
    </div>
</div>

<!-- Styles -->
<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
        cursor: pointer;
    }
    .hover-card div, .hover-card i {
        pointer-events: none;
    }

    /* Quick Actions Styling */
    #quickActionsList .list-group-item {
        background-color: transparent;
        border-color: rgba(255,255,255,0.1);
        transition: all 0.2s;
    }
    #quickActionsList .list-group-item:hover {
        background-color: rgba(255,255,255,0.1);
        transform: translateX(5px);
    }

    /* Icon Circle Styling */
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
</style>

<!-- Chart Scripts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var options = {
            series: [{{ $presentCount ?? 0 }}, {{ $absentCount ?? 0 }}, {{ $lateCount ?? 0 }}, {{ $leaveCount ?? 0 }}],
            chart: {
                type: 'donut',
                height: 300,
                foreColor: '#fff',
                toolbar: { show: true }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            name: { show: true, fontSize: '16px', color: '#fff' },
                            value: { show: true, fontSize: '20px', color: '#fff' },
                            total: {
                                show: true,
                                label: 'Total',
                                color: '#fff',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: true, style: { fontSize: '12px', fontFamily: 'Nunito' } },
            labels: ['Present', 'Absent', 'Late', 'Leave'],
            colors: ['#1cc88a', '#e74a3b', '#f6c23e', '#fd7e14'],
            legend: {
                position: 'bottom',
                fontFamily: 'Nunito',
                fontSize: '14px',
                labels: { colors: '#ffffff' }
            }
        };

        var chart = new ApexCharts(document.querySelector("#attendanceChart"), options);
        chart.render();
    });
</script>

@endsection