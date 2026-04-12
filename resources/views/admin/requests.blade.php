@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Profile Requests</h2>
            <p class="text-light opacity-75 mb-0">Manage student admission requests</p>
        </div>
    </div>

    <!-- Buttons -->
    <div class="card border-light shadow mb-4">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap gap-2">
                
                <!-- All Requests (No Count) -->
                <a href="{{ route('admin.requests') }}" 
                   class="btn {{ !request('status') ? 'btn-filter-active' : 'btn-outline-light' }} btn-sm">
                    All Requests
                </a>

                <!-- Pending (With Count) -->
                <a href="{{ route('admin.requests', ['status' => 'pending']) }}" 
                   class="btn {{ request('status') == 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }} btn-sm">
                    <i class="fas fa-clock me-1"></i> Pending
                    <span class="badge {{ request('status') == 'pending' ? 'bg-dark text-light' : 'bg-warning text-dark' }} ms-2">{{ $countPending }}</span>
                </a>

                <!-- Approved (No Count) -->
                <a href="{{ route('admin.requests', ['status' => 'approved']) }}" 
                   class="btn {{ request('status') == 'approved' ? 'btn-success' : 'btn-outline-success' }} btn-sm">
                    <i class="fas fa-check me-1"></i> Approved
                </a>

                <!-- Rejected (No Count) -->
                <a href="{{ route('admin.requests', ['status' => 'rejected']) }}" 
                   class="btn {{ request('status') == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }} btn-sm">
                    <i class="fas fa-times me-1"></i> Rejected
                </a>
            </div>
        </div>
    </div>

    <!-- Requests List -->
    <div class="card border-light shadow">
        <div class="card-body p-0">
            @if($requests->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-transparent">
                        <tr>
                            <th class="border-0 text-light opacity-75 ps-4">Sr#</th>
                            <th class="border-0 text-light opacity-75">Student Name</th>
                            <th class="border-0 text-light opacity-75">Roll No</th>
                            <th class="border-0 text-light opacity-75">Department</th>
                            <th class="border-0 text-light opacity-75">Course</th>
                            <th class="border-0 text-light opacity-75">Contact</th>
                            <th class="border-0 text-light opacity-75 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                        <tr class="{{ $req->status == 'rejected' ? 'bg-danger bg-opacity-10' : '' }}">
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold text-white">{{ $req->student_name }}</div>
                                <small class="text-light opacity-50">Father: {{ $req->father_name }}</small>
                            </td>
                            <td class="text-white">{{ $req->roll_no }}</td>
                            <td class="text-white">{{ $req->department->name ?? 'N/A' }}</td>
                            <td>
                                @if($req->course)
                                    <span class="badge bg-info bg-opacity-25 text-info">{{ $req->course }}</span>
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                            <td class="text-white">{{ $req->contact_number }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    
                                    @if($req->status == 'pending')
                                        <a href="{{ route('students.create', ['request_id' => $req->id]) }}" class="btn btn-sm btn-outline-primary" title="Approve">
                                            <i class="fas fa-user-plus"></i>
                                        </a>
                                        <form action="{{ route('delete.request', $req->id) }}" method="POST" onsubmit="return confirm('Reject?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>

                                    @elseif($req->status == 'approved')
                                        @php $student = \App\Models\Student::where('user_id', $req->user_id)->first(); @endphp
                                        @if($student)
                                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('admin.profile-request.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Delete?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                    @elseif($req->status == 'rejected')
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewProfileRequestModal"
                                                data-name="{{ $req->student_name }}"
                                                data-father="{{ $req->father_name }}"
                                                data-roll="{{ $req->roll_no }}"
                                                data-dept="{{ $req->department->name ?? 'N/A' }}"
                                                data-course="{{ $req->course ?? 'N/A' }}"
                                                data-semester="{{ $req->semester }}"
                                                data-session="{{ $req->session }}"
                                                data-contact="{{ $req->contact_number }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <form action="{{ route('admin.profile-request.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Delete?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
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
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-2x mb-2 opacity-50"></i>
                <p class="text-light opacity-50 mb-0">No records found</p>
            </div>
            @endif
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="viewProfileRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-light shadow" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
            <div class="modal-header border-light">
                <h5 class="modal-title text-white">Rejected Request Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-transparent"><tr><th class="border-0 text-light opacity-75 ps-3" style="width: 40%;">Field</th><th class="border-0 text-light opacity-75">Details</th></tr></thead>
                        <tbody>
                            <tr><td class="ps-3 text-light opacity-75">Student Name</td><td class="text-white fw-bold" id="modalName">N/A</td></tr>
                            <tr><td class="ps-3 text-light opacity-75">Father Name</td><td class="text-white" id="modalFather">N/A</td></tr>
                            <tr><td class="ps-3 text-light opacity-75">Roll Number</td><td class="text-white" id="modalRoll">N/A</td></tr>
                            <tr><td class="ps-3 text-light opacity-75">Department</td><td class="text-white" id="modalDept">N/A</td></tr>
                            <tr><td class="ps-3 text-light opacity-75">Course Code</td><td class="text-white" id="modalCourse">N/A</td></tr>
                            <tr><td class="ps-3 text-light opacity-75">Semester</td><td class="text-white" id="modalSemester">N/A</td></tr>
                            <tr><td class="ps-3 text-light opacity-75">Session</td><td class="text-white" id="modalSession">N/A</td></tr>
                            <tr><td class="ps-3 text-light opacity-75">Contact</td><td class="text-white" id="modalContact">N/A</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="modal-footer border-light">
                <span class="badge bg-danger fs-6"><i class="fas fa-times me-1"></i> Status: Rejected</span>
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* 1. All Requests Active (Purple) */
    .btn-filter-active {
        background: linear-gradient(135deg, rgba(118, 75, 162, 0.8), rgba(118, 75, 162, 0.6)) !important;
        border-color: rgba(118, 75, 162, 0.8) !important;
        color: #ffffff !important;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(118, 75, 162, 0.4);
    }
    
    /* 2. Pending Inactive (Outline): Yellow Text & Icon */
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
    
    /* 3. Approved Inactive (Outline): Green Text & Icon */
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

    /* 4. Rejected Inactive (Outline): Red Text & Icon */
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
    
    /* 5. Others */
    .btn-outline-light i { color: #fff; }
</style>

<script>
    // Modal Handler
    var viewProfileRequestModal = document.getElementById('viewProfileRequestModal')
    viewProfileRequestModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget
        document.getElementById('modalName').textContent = button.getAttribute('data-name')
        document.getElementById('modalFather').textContent = button.getAttribute('data-father')
        document.getElementById('modalRoll').textContent = button.getAttribute('data-roll')
        document.getElementById('modalDept').textContent = button.getAttribute('data-dept')
        document.getElementById('modalCourse').textContent = button.getAttribute('data-course')
        document.getElementById('modalSemester').textContent = button.getAttribute('data-semester')
        document.getElementById('modalSession').textContent = button.getAttribute('data-session')
        document.getElementById('modalContact').textContent = button.getAttribute('data-contact')
    })
</script>
@endsection