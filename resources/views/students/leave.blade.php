@extends('layouts.app')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Leave Requests</h1>
    <p style="font-size:.82rem;color:#6b7280;margin:0;">Submit and track your leave applications</p>
</div>

<div class="row g-4">

    <!-- Submit Form -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <div class="hico"><i class="fas fa-calendar-plus"></i></div>
                Submit Leave Request
            </div>
            <div class="card-body p-4">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('student.submitLeave') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Leave Date</label>
                        <input type="date" name="leave_date"
                               class="form-control"
                               style="background:#fff;border:1.5px solid #d1d5db;color:#111827;padding:10px 13px;"
                               min="{{ date('Y-m-d') }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" rows="5"
                                  class="form-control"
                                  style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                                  placeholder="Write your reason here..." required></textarea>
                        <div class="form-text">Explain your reason in detail.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane me-2"></i> Submit Request
                    </button>
                </form>

            </div>
        </div>
    </div>

    <!-- Leave History -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <div class="hico"><i class="fas fa-history"></i></div>
                My Leave History
            </div>
            <div class="card-body p-0">
                @if($leaves->count() > 0)
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Reason</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $leave)
                            <tr>
                                <td style="color:#9ca3af;">{{ $loop->iteration }}</td>
                                <td style="color:#374151;font-weight:600;white-space:nowrap;">
                                    {{ \Carbon\Carbon::parse($leave->leave_date)->format('d M Y') }}
                                </td>
                                <td style="color:#6b7280;max-width:160px;">
                                    <span class="d-inline-block text-truncate" style="max-width:150px;">
                                        {{ $leave->reason }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($leave->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($leave->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewLeaveModal"
                                            data-date="{{ \Carbon\Carbon::parse($leave->leave_date)->format('d F Y') }}"
                                            data-reason="{{ $leave->reason }}"
                                            data-status="{{ $leave->status }}"
                                            data-created="{{ \Carbon\Carbon::parse($leave->created_at)->format('d M Y, h:i A') }}"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5" style="color:#9ca3af;">
                    <i class="fas fa-inbox fa-3x mb-3 d-block opacity-50"></i>
                    <h5>No History</h5>
                    <p>You haven't submitted any leave requests yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- View Leave Modal -->
<div class="modal fade" id="viewLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight:700;font-size:.95rem;">
                    <i class="fas fa-calendar-check me-2 text-info"></i> Leave Application Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table mb-0">
                    <tbody>
                        <tr>
                            <td style="color:#9ca3af;font-weight:600;width:38%;">Leave Date</td>
                            <td style="font-weight:700;color:#111827;" id="lv-date"></td>
                        </tr>
                        <tr>
                            <td style="color:#9ca3af;font-weight:600;">Submitted On</td>
                            <td style="color:#374151;" id="lv-created"></td>
                        </tr>
                        <tr>
                            <td style="color:#9ca3af;font-weight:600;">Status</td>
                            <td id="lv-status"></td>
                        </tr>
                        <tr>
                            <td style="color:#9ca3af;font-weight:600;vertical-align:top;">Reason</td>
                            <td style="color:#374151;" id="lv-reason"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('viewLeaveModal').addEventListener('show.bs.modal', function(e) {
    var b = e.relatedTarget;
    document.getElementById('lv-date').textContent    = b.getAttribute('data-date');
    document.getElementById('lv-reason').textContent  = b.getAttribute('data-reason');
    document.getElementById('lv-created').textContent = b.getAttribute('data-created');

    var status = b.getAttribute('data-status');
    var statusEl = document.getElementById('lv-status');
    if (status === 'pending') {
        statusEl.innerHTML = '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Pending</span>';
    } else if (status === 'approved') {
        statusEl.innerHTML = '<span class="badge bg-success"><i class="fas fa-check me-1"></i> Approved</span>';
    } else {
        statusEl.innerHTML = '<span class="badge bg-danger"><i class="fas fa-times me-1"></i> Rejected</span>';
    }
});
</script>
@endsection