@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Leave Approvals</h2>
            <p class="text-light opacity-75 mb-0">Manage student leave requests</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-light shadow mb-4">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap gap-2">
                
                <!-- All Requests -->
                <a href="{{ route('admin.leaves') }}" 
                   class="btn {{ !request('status') ? 'btn-filter-active' : 'btn-outline-light' }} btn-sm">
                    All Requests
                </a>

                <!-- Pending (With Count) -->
                <a href="{{ route('admin.leaves', ['status' => 'pending']) }}" 
                   class="btn {{ request('status') == 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }} btn-sm">
                    <i class="fas fa-clock me-1"></i> Pending
                    <span class="badge {{ request('status') == 'pending' ? 'bg-dark text-light' : 'bg-warning text-dark' }} ms-2">{{ $countPending }}</span>
                </a>

                <!-- Approved -->
                <a href="{{ route('admin.leaves', ['status' => 'approved']) }}" 
                   class="btn {{ request('status') == 'approved' ? 'btn-success' : 'btn-outline-success' }} btn-sm">
                    <i class="fas fa-check me-1"></i> Approved
                </a>

                <!-- Rejected -->
                <a href="{{ route('admin.leaves', ['status' => 'rejected']) }}" 
                   class="btn {{ request('status') == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }} btn-sm">
                    <i class="fas fa-times me-1"></i> Rejected
                </a>
            </div>
        </div>
    </div>

    <!-- Requests List Card -->
    @if($leaves->count() > 0)
    <div class="card border-light shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-transparent">
                        <tr>
                            <th class="border-0 text-light opacity-75 ps-4">Sr#</th>
                            <th class="border-0 text-light opacity-75">Student Name</th>
                            <th class="border-0 text-light opacity-75">Date</th>
                            <th class="border-0 text-light opacity-75 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves as $leave)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td>
                                <div>{{ $leave->student->student_name ?? 'N/A' }}</div>
                            </td>
                            <td>{{ $leave->leave_date }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-1">
                                    
                                    <!-- View Button -->
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#viewLeaveModal"
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

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.deleteLeave', $leave->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
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
        
        <!-- Pagination -->
        @if($leaves->hasPages())
        <div class="card-footer d-flex justify-content-center">
            {{ $leaves->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="card border-light shadow">
        <div class="card-body text-center py-5">
            <i class="fas fa-calendar-check fa-3x mb-3 opacity-50"></i>
            <h5>No Leave Requests Found</h5>
            <p class="text-light opacity-50">No requests match your filter.</p>
        </div>
    </div>
    @endif

</div>

<!-- View Leave Modal -->
<div class="modal fade" id="viewLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-light shadow" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
            <div class="modal-header border-light">
                <h5 class="modal-title text-white">Leave Request Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-transparent">
                            <tr>
                                <th class="border-0 text-light opacity-75 ps-3" style="width: 30%;">Field</th>
                                <th class="border-0 text-light opacity-75">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-3 text-light opacity-75">Student Name</td>
                                <td class="text-white fw-bold" id="modalStudentName">N/A</td>
                            </tr>
                             <tr>
                                <td class="ps-3 text-light opacity-75">Roll Number</td>
                                <td class="text-white" id="modalRoll">N/A</td>
                            </tr>
                            <tr>
                                <td class="ps-3 text-light opacity-75">Department</td>
                                <td class="text-white" id="modalDept">N/A</td>
                            </tr>
                            <tr>
                                <td class="ps-3 text-light opacity-75">Semester</td>
                                <td class="text-white" id="modalSem">N/A</td>
                            </tr>
                            <tr>
                                <td class="ps-3 text-light opacity-75">Session</td>
                                <td class="text-white" id="modalSession">N/A</td>
                            </tr>
                            <tr>
                                <td class="ps-3 text-light opacity-75">Leave Date</td>
                                <td class="text-white" id="modalDate">N/A</td>
                            </tr>
                            <tr>
                                <td class="ps-3 text-light opacity-75 align-top">Reason</td>
                                <td class="text-white" id="modalReason">Reason text...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="modal-footer border-light justify-content-between">
                <div>
                    <span id="modalStatusBadge" class="badge fs-6"></span>
                </div>

                <div class="d-flex gap-2">
                    <a href="#" id="modalApproveBtn" class="btn btn-success d-none">
                        <i class="fas fa-check me-1"></i> Approve
                    </a>
                    <a href="#" id="modalRejectBtn" class="btn btn-danger d-none">
                        <i class="fas fa-times me-1"></i> Reject
                    </a>
                    
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Active Button Style (Purple) */
    .btn-filter-active {
        background: linear-gradient(135deg, rgba(118, 75, 162, 0.8), rgba(118, 75, 162, 0.6)) !important;
        border-color: rgba(118, 75, 162, 0.8) !important;
        color: #ffffff !important;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(118, 75, 162, 0.4);
    }
    
    /* Pending Inactive (Outline): Yellow Text & Icon */
    .btn-outline-warning {
        color: #ffc107 !important;
        border-color: #ffc107 !important;
    }
    .btn-outline-warning i {
        color: #ffc107 !important;
    }
    .btn-outline-warning:hover {
        color: #000 !important;
        background-color: #ffc107 !important;
    }
    .btn-outline-warning:hover i {
        color: #000 !important;
    }
    
    /* Pending Active: Yellow BG, Black Text/Icon */
    .btn-warning.text-dark {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        color: #000 !important; 
    }
    .btn-warning.text-dark i {
        color: #000 !important;
    }
    
    /* Approved Inactive (Outline): Green Text & Icon */
    .btn-outline-success {
        color: #198754 !important;
        border-color: #198754 !important;
    }
    .btn-outline-success i {
        color: #198754 !important;
    }
    .btn-outline-success:hover {
        color: #fff !important;
        background-color: #198754 !important;
    }
    .btn-outline-success:hover i {
        color: #fff !important;
    }
    /* Approved Active (Green): White Text & Icon */
    .btn-success {
        color: #fff !important;
        background-color: #198754 !important;
        border-color: #198754 !important;
    }
    .btn-success i {
        color: #fff !important;
    }

    /* Rejected Inactive (Outline): Red Text & Icon */
    .btn-outline-danger {
        color: #dc3545 !important;
        border-color: #dc3545 !important;
    }
    .btn-outline-danger i {
        color: #dc3545 !important;
    }
    .btn-outline-danger:hover {
        color: #fff !important;
        background-color: #dc3545 !important;
    }
    .btn-outline-danger:hover i {
        color: #fff !important;
    }
    
    /* Rejected Active (Red): White Text & Icon */
    .btn-danger {
        color: #fff !important;
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }
    .btn-danger i {
        color: #fff !important;
    }
    
    /* Others */
    .btn-outline-light i { color: #fff; }
</style>

<script>
    var viewLeaveModal = document.getElementById('viewLeaveModal')
    viewLeaveModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget
        
        var student = button.getAttribute('data-student')
        var roll = button.getAttribute('data-roll')
        var department = button.getAttribute('data-department')
        var semester = button.getAttribute('data-semester')
        var session = button.getAttribute('data-session')
        var date = button.getAttribute('data-date')
        var reason = button.getAttribute('data-reason')
        var status = button.getAttribute('data-status')
        var approveUrl = button.getAttribute('data-approve-url')
        var rejectUrl = button.getAttribute('data-reject-url')

        viewLeaveModal.querySelector('#modalStudentName').textContent = student
        viewLeaveModal.querySelector('#modalRoll').textContent = roll
        viewLeaveModal.querySelector('#modalDept').textContent = department
        viewLeaveModal.querySelector('#modalSem').textContent = semester
        viewLeaveModal.querySelector('#modalSession').textContent = session ?? 'N/A'
        viewLeaveModal.querySelector('#modalDate').textContent = date
        viewLeaveModal.querySelector('#modalReason').textContent = reason

        var statusBadge = viewLeaveModal.querySelector('#modalStatusBadge');
        statusBadge.className = 'badge fs-6'; 
        
        var approveBtn = viewLeaveModal.querySelector('#modalApproveBtn')
        var rejectBtn = viewLeaveModal.querySelector('#modalRejectBtn')

        approveBtn.classList.add('d-none');
        rejectBtn.classList.add('d-none');

        if(status === 'pending') {
            statusBadge.classList.add('bg-warning', 'text-dark');
            statusBadge.textContent = 'Pending';
            
            approveBtn.href = approveUrl;
            approveBtn.classList.remove('d-none');
            
            rejectBtn.href = rejectUrl;
            rejectBtn.classList.remove('d-none');
        } else if(status === 'approved') {
            statusBadge.classList.add('bg-success');
            statusBadge.textContent = 'Approved';
        } else {
            statusBadge.classList.add('bg-danger');
            statusBadge.textContent = 'Rejected';
        }
    })
</script>
@endsection