@extends('layouts.app')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Profile Requests</h1>
    <p style="font-size:.82rem;color:#6b7280;margin:0;">Manage student profile setup requests</p>
</div>

<!-- Filter Tabs -->
<div class="card mb-4">
    <div class="card-body py-2 px-3">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.requests') }}"
               class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">
                All Requests
            </a>
            <a href="{{ route('admin.requests', ['status' => 'pending']) }}"
               class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
                <i class="fas fa-clock me-1"></i> Pending
                <span class="badge ms-1 {{ request('status') == 'pending' ? 'bg-dark' : 'bg-warning text-dark' }}">{{ $countPending }}</span>
            </a>
            <a href="{{ route('admin.requests', ['status' => 'approved']) }}"
               class="btn btn-sm {{ request('status') == 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                <i class="fas fa-check me-1"></i> Approved
            </a>
            <a href="{{ route('admin.requests', ['status' => 'rejected']) }}"
               class="btn btn-sm {{ request('status') == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                <i class="fas fa-times me-1"></i> Rejected
            </a>
        </div>
    </div>
</div>

<!-- Requests Table -->
<div class="card">
    <div class="card-header">
        <div class="hico"><i class="fas fa-inbox"></i></div>
        All Requests
    </div>
    <div class="card-body p-0">
        @if($requests->count() > 0)
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Roll No</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                    <tr style="{{ $req->status == 'rejected' ? 'background:#fef2f2;' : '' }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div style="font-weight:700;color:#111827;">{{ $req->student_name }}</div>
                            <div style="font-size:.75rem;color:#9ca3af;">Father: {{ $req->father_name }}</div>
                        </td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $req->roll_no }}</span></td>
                        <td style="color:#374151;">{{ $req->department->name ?? 'N/A' }}</td>
                        <td>
                            @if($req->course)
                                <span class="badge bg-info bg-opacity-10 text-info fw-bold">{{ $req->course }}</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </td>
                        <td style="color:#374151;">{{ $req->contact_number }}</td>
                        <td>
                            @if($req->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($req->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">

                                @if($req->status == 'pending')
                                    <a href="{{ route('students.create', ['request_id' => $req->id]) }}"
                                       class="btn btn-sm btn-outline-primary" title="Approve">
                                        <i class="fas fa-user-plus"></i>
                                    </a>
                                    <form action="{{ route('delete.request', $req->id) }}" method="POST"
                                          onsubmit="return confirm('Reject this request?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>

                                @elseif($req->status == 'approved')
                                    @php $student = \App\Models\Student::where('user_id', $req->user_id)->first(); @endphp
                                    @if($student)
                                    <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-outline-info" title="View Student">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                    <form action="{{ route('admin.profile-request.destroy', $req->id) }}" method="POST"
                                          onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                @elseif($req->status == 'rejected')
                                    <button type="button" class="btn btn-sm btn-outline-info"
                                            data-bs-toggle="modal" data-bs-target="#viewProfileModal"
                                            data-name="{{ $req->student_name }}"
                                            data-father="{{ $req->father_name }}"
                                            data-roll="{{ $req->roll_no }}"
                                            data-dept="{{ $req->department->name ?? 'N/A' }}"
                                            data-course="{{ $req->course ?? 'N/A' }}"
                                            data-semester="{{ $req->semester }}"
                                            data-session="{{ $req->session }}"
                                            data-contact="{{ $req->contact_number }}"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form action="{{ route('admin.profile-request.destroy', $req->id) }}" method="POST"
                                          onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5" style="color:#9ca3af;">
            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-50"></i>
            <h5>No Records Found</h5>
            <p>No requests match the selected filter.</p>
        </div>
        @endif
    </div>
</div>

<!-- View Rejected Request Modal -->
<div class="modal fade" id="viewProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight:700;font-size:.95rem;">
                    <i class="fas fa-id-card me-2 text-danger"></i> Rejected Request Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table mb-0">
                    <tbody>
                        <tr><td style="color:#9ca3af;font-weight:600;width:38%;">Student Name</td><td style="font-weight:700;color:#111827;" id="pr-name"></td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Father Name</td><td style="color:#374151;" id="pr-father"></td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Roll Number</td><td style="color:#374151;" id="pr-roll"></td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Department</td><td style="color:#374151;" id="pr-dept"></td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Course Code</td><td style="color:#374151;" id="pr-course"></td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Semester</td><td style="color:#374151;" id="pr-semester"></td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Session</td><td style="color:#374151;" id="pr-session"></td></tr>
                        <tr><td style="color:#9ca3af;font-weight:600;">Contact</td><td style="color:#374151;" id="pr-contact"></td></tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <span class="badge bg-danger">Status: Rejected</span>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('viewProfileModal').addEventListener('show.bs.modal', function(e) {
    var b = e.relatedTarget;
    document.getElementById('pr-name').textContent    = b.getAttribute('data-name');
    document.getElementById('pr-father').textContent  = b.getAttribute('data-father');
    document.getElementById('pr-roll').textContent    = b.getAttribute('data-roll');
    document.getElementById('pr-dept').textContent    = b.getAttribute('data-dept');
    document.getElementById('pr-course').textContent  = b.getAttribute('data-course');
    document.getElementById('pr-semester').textContent= b.getAttribute('data-semester');
    document.getElementById('pr-session').textContent = b.getAttribute('data-session');
    document.getElementById('pr-contact').textContent = b.getAttribute('data-contact');
});
</script>
@endsection