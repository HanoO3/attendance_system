@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">My Attendance History</h2>
            <p class="text-light opacity-75 mb-0">View your detailed attendance record</p>
        </div>
    </div>

    <div class="card border-light shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="myAttendanceTable">
                    <thead class="bg-transparent">
                        <tr>
                            <th class="border-0 text-light opacity-75">Date</th>
                            <th class="border-0 text-light opacity-75">Status</th>
                            <th class="border-0 text-light opacity-75">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $row)
                        <tr>
                            <td>{{ $row->attendance_date }}</td>
                            <td>
                                @if($row->status == 'present')
                                    <span class="badge bg-success bg-opacity-75">Present</span>
                                @elseif($row->status == 'absent')
                                    <span class="badge bg-danger bg-opacity-75">Absent</span>
                                @elseif($row->status == 'late')
                                    <!-- Yellow for Late -->
                                    <span class="badge" style="background-color: #FFC107; color: #000;">Late</span>
                                @elseif($row->status == 'leave')
                                    <!-- Orange for Leave -->
                                    <span class="badge" style="background-color: #fd7e14; color: #fff;">Leave</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($row->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $row->remarks ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
                                <h5>No Records</h5>
                                <p class="text-light opacity-50 mb-0">No attendance data found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
    /* Table Row Hover */
    #myAttendanceTable tbody tr:hover {
        background-color: rgba(255,255,255,0.05) !important;
    }
</style>
@endsection