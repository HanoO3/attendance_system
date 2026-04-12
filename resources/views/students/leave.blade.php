@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Leave Requests</h2>
            <p class="text-light opacity-75 mb-0">Submit and track your leave applications</p>
        </div>
    </div>

    <div class="row">
        <!-- Form Card -->
        <div class="col-lg-5 mb-4">
            <div class="card border-light shadow h-100">
                <div class="card-header py-3">
                    <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i> Submit Leave Request</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('student.submitLeave') }}" method="POST">
                        @csrf
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label text-white">Leave Date</label>
                            <input type="date" name="leave_date" class="form-control bg-transparent text-white border-light" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Reason</label>
                            <textarea name="reason" class="form-control bg-transparent text-white border-light placeholder-white" rows="4" 
                                      placeholder="Write your reason here..." required></textarea>
                            <small class="text-light opacity-50">Explain your reason in detail.</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-2">
                            <i class="fas fa-paper-plane me-2"></i> Submit Request
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- History Card (Updated with View Button) -->
        <div class="col-lg-7 mb-4">
            <div class="card border-light shadow h-100">
                <div class="card-header py-3">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i> My Leave History</h5>
                </div>
                <div class="card-body p-0">
                    @if($leaves->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-transparent">
                                <tr>
                                    <th class="border-0 text-light opacity-75 ps-3">Date</th>
                                    <th class="border-0 text-light opacity-75">Reason</th>
                                    <th class="border-0 text-light opacity-75 text-center">Status</th>
                                    <th class="border-0 text-light opacity-75 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaves as $leave)
                                <tr>
                                    <td class="ps-3 text-white">
                                        {{ \Carbon\Carbon::parse($leave->leave_date)->format('d M Y') }}
                                    </td>
                                    <td class="text-white">
                                        <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                            {{ $leave->reason }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($leave->status == 'pending')
                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Pending</span>
                                        @elseif($leave->status == 'approved')
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i> Approved</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Rejected</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <!-- View Button -->
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewStudentLeaveModal"
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
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                        <h5>No History</h5>
                        <p class="text-light opacity-50">You haven't submitted any leave requests yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Student Leave View Modal -->
<div class="modal fade" id="viewStudentLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-light shadow" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
            <div class="modal-header border-light">
                <h5 class="modal-title text-white">Application Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-transparent">
                            <tr>
                                <th class="border-0 text-light opacity-75 ps-3" style="width: 40%;">Field</th>
                                <th class="border-0 text-light opacity-75">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-3 text-light opacity-75">Leave Date</td>
                                <td class="text-white fw-bold" id="modalDate">N/A</td>
                            </tr>
                            <tr>
                                <td class="ps-3 text-light opacity-75">Submitted On</td>
                                <td class="text-white" id="modalCreated">N/A</td>
                            </tr>
                            <tr>
                                <td class="ps-3 text-light opacity-75">Status</td>
                                <td id="modalStatus">N/A</td>
                            </tr>
                            <tr>
                                <td class="ps-3 text-light opacity-75 align-top">Reason</td>
                                <td class="text-white" id="modalReason">Reason text...</td>
                            </tr>
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

<style>
    .placeholder-white::placeholder { color: #ffffff !important; opacity: 0.7; }
    .form-control:focus {
        background-color: rgba(255, 255, 255, 0.05);
        border-color: #6244a2;
        color: #ffffff;
        box-shadow: 0 0 0 0.2rem rgba(98, 68, 162, 0.25);
    }
</style>

<script>
    // Modal Handler
    var viewStudentLeaveModal = document.getElementById('viewStudentLeaveModal')
    viewStudentLeaveModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget
        
        // Get Data
        var date = button.getAttribute('data-date')
        var reason = button.getAttribute('data-reason')
        var status = button.getAttribute('data-status')
        var created = button.getAttribute('data-created')

        // Set Data
        document.getElementById('modalDate').textContent = date
        document.getElementById('modalReason').textContent = reason
        document.getElementById('modalCreated').textContent = created
        
        // Set Status Badge
        var statusTd = document.getElementById('modalStatus');
        statusTd.innerHTML = ''; // Clear previous

        if(status === 'pending') {
            statusTd.innerHTML = '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Pending</span>';
        } else if(status === 'approved') {
            statusTd.innerHTML = '<span class="badge bg-success"><i class="fas fa-check me-1"></i> Approved</span>';
        } else {
            statusTd.innerHTML = '<span class="badge bg-danger"><i class="fas fa-times me-1"></i> Rejected</span>';
        }
    })
</script>
@endsection