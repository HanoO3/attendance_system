@extends('layouts.app')
@section('content')
<div style="margin-bottom:22px;">
    <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">My Attendance History</h1>
    <p style="font-size:.82rem;color:#6b7280;margin:0;">View your detailed attendance record</p>
</div>
<div class="card">
    <div class="card-header">
        <div class="hico"><i class="fas fa-clipboard-list"></i></div>
        Attendance Records
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Subject</th>
                        <th class="text-center">Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $row)
                    <tr>
                        <td style="color:#9ca3af;">{{ $loop->iteration }}</td>
                        <td style="color:#374151;font-weight:600;">{{ \Carbon\Carbon::parse($row->attendance_date)->format('d M Y') }}</td>
                        <td style="color:#6b7280;">{{ $row->subject->name ?? '-' }}</td>
                        <td class="text-center">
                            @if($row->status == 'present')
                                <span class="badge bg-success">Present</span>
                            @elseif($row->status == 'absent')
                                <span class="badge bg-danger">Absent</span>
                            @elseif($row->status == 'late')
                                <span class="badge bg-warning text-dark">Late</span>
                            @elseif($row->status == 'leave')
                                <span class="badge" style="background:#fd7e14;color:#fff;">Leave</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($row->status) }}</span>
                            @endif
                        </td>
                        <td style="color:#6b7280;">{{ $row->remarks ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5" style="color:#9ca3af;">
                            <i class="fas fa-folder-open fa-3x mb-3 d-block opacity-50"></i>
                            <h5>No Records Found</h5>
                            <p>No attendance data found yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection