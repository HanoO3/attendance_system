@extends('layouts.app')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Leave Approvals</h1>
    <p style="font-size:.82rem;color:#6b7280;margin:0;">Manage student leave requests</p>
</div>

<!-- Filter Tabs -->
<div class="card mb-4">
    <div class="card-body py-2 px-3">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.leaves') }}"
               class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">
                All Requests
            </a>
            <a href="{{ route('admin.leaves', ['status' => 'pending']) }}"
               class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
                <i class="fas fa-clock me-1"></i> Pending
                <span class="badge ms-1 {{ request('status') == 'pending' ? 'bg-dark' : 'bg-warning text-dark' }}">{{ $countPending }}</span>
            </a>
            <a href="{{ route('admin.leaves', ['status' => 'approved']) }}"
               class="btn btn-sm {{ request('status') == 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                <i class="fas fa-check me-1"></i> Approved
            </a>
            <a href="{{ route('admin.leaves', ['status' => 'rejected']) }}"
               class="btn btn-sm {{ request('status') == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                <i class="fas fa-times me-1"></i> Rejected
            </a>
        </div>
    </div>
</div>

<!-- Leave Requests Table -->
@if($leaves->count() > 0)
<div class="card">
    <div class="card-header">
        <div class="hico"><i class="fas fa-calendar-check"></i></div>
        Leave Requests
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Roll No</th>
                        <th>Department</th>
                        <th>Leave Date</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaves as $leave)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="font-weight:700;color:#111827;">{{ $leave->student->student_name ?? 'N/A' }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $leave->student->roll_number ?? 'N/A' }}</span></td>
                        <td style="color:#374151;">{{ $leave->student->department->name ?? 'N/A' }}</td>
                        <td style="color:#6b7280;">{{ $leave->leave_date }}</td>
                        <td>
                            @if($leave->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($leave->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <button type="button" class="btn btn-sm btn-outline-info"
                                        data-bs-toggle="modal" data-bs-target="#viewLeaveModal"
                                        data-student="{{ $leave->student->student_name ?? 'N/A' }}"
                                        data-roll="{{ $leave->student->roll_number ?? 'N/A' }}"
                                        data-department="{{ $leave->student->department->name ?? 'N/A' }}"
                                        data-semester="{{ $leave->student->semester ?? 'N/A' }}"
                                        data-session="{{ $leave->student->session ?? 'N/A' }}"
                                        data-date="{{ $leave->leave_date }}"
                                        data-reason="{{ $leave->reason }}"
                                        data-status="{{ $leave->status }}"
                                        data-approve-url="{{ route('admin.approveLeave', $leave->id) }}"
                                        data-reject-url="{{ route('admin.rejectLeave', $leave->id) }}"
                                        title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <form action="{{ route('admin.deleteLeave', $leave->id) }}" method="POST"
                                      style="display:inline;" onsubmit="return confirm('Delete this leave request?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($leaves->hasPages())
    <div class="card-footer d-flex justify-content-center" style="background:#f8fafc;border-top:1px solid #e5e7eb;padding:12px;">
        {{ $leaves->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@else
<div class="card">
    <div class="card-body text-center py-5" style="color:#9ca3af;">
        <i class="fas fa-calendar-check fa-3x mb-3 d-block opacity-50"></i>
        <h5>No Leave Requests Found</h5>
        <p>No requests match your filter.</p>
    </div>
</div>
@endif

<!-- View Leave Modal -->
<div class="modal fade" id="viewLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight:700;font-size:.95rem;">
                    <i class="fas fa-calendar-check me-2 text-info"></i> Leave Request Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table mb-0">
                    <tbody>
                        <tr><td style="color:#9ca3af;font-weight:600;width:35%;">Student Name</td><td style="font-weight:700;color:#111827;" id="lv-student"></td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Roll Number</td><td style="color:#374151;" id="lv-roll"></td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Department</td><td style="color:#374151;" id="lv-dept"></td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Semester</td><td style="color:#374151;" id="lv-sem"></td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Session</td><td style="color:#374151;" id="lv-session"></td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Leave Date</td><td style="color:#374151;" id="lv-date"></td></tr>
                        <tr>
                            <td style="color:#9ca3af;font-weight:600;vertical-align:top;">Reason</td>
                            <td style="color:#374151;" id="lv-reason"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <span id="lv-status-badge" class="badge fs-6"></span>
                <div class="d-flex gap-2">
                    <a href="#" id="lv-approve-btn" class="btn btn-success btn-sm d-none">
                        <i class="fas fa-check me-1"></i> Approve
                    </a>
                    <a href="#" id="lv-reject-btn" class="btn btn-danger btn-sm d-none">
                        <i class="fas fa-times me-1"></i> Reject
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('viewLeaveModal').addEventListener('show.bs.modal', function(e) {
    var b = e.relatedTarget;
    document.getElementById('lv-student').textContent = b.getAttribute('data-student');
    document.getElementById('lv-roll').textContent    = b.getAttribute('data-roll');
    document.getElementById('lv-dept').textContent    = b.getAttribute('data-department');
    document.getElementById('lv-sem').textContent     = b.getAttribute('data-semester');
    document.getElementById('lv-session').textContent = b.getAttribute('data-session') || 'N/A';
    document.getElementById('lv-date').textContent    = b.getAttribute('data-date');
    document.getElementById('lv-reason').textContent  = b.getAttribute('data-reason');

    var status     = b.getAttribute('data-status');
    var badge      = document.getElementById('lv-status-badge');
    var approveBtn = document.getElementById('lv-approve-btn');
    var rejectBtn  = document.getElementById('lv-reject-btn');

    badge.className = 'badge fs-6';
    approveBtn.classList.add('d-none');
    rejectBtn.classList.add('d-none');

    if (status === 'pending') {
        badge.classList.add('bg-warning', 'text-dark');
        badge.textContent = 'Pending';
        approveBtn.href = b.getAttribute('data-approve-url');
        rejectBtn.href  = b.getAttribute('data-reject-url');
        approveBtn.classList.remove('d-none');
        rejectBtn.classList.remove('d-none');
    } else if (status === 'approved') {
        badge.classList.add('bg-success');
        badge.textContent = 'Approved';
    } else {
        badge.classList.add('bg-danger');
        badge.textContent = 'Rejected';
    }
});
</script>
@endsection