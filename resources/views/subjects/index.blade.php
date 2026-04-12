@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Subjects Management</h2>
        <a href="{{ route('subjects.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Add Subject</a>
    </div>

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

    <div class="card border-light shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-transparent">
                        <tr>
                            <th class="border-0 text-light opacity-75 ps-4">Name</th>
                            <th class="border-0 text-light opacity-75">Code</th>
                            <th class="border-0 text-light opacity-75">Department</th>
                            <th class="border-0 text-light opacity-75">Semester</th>
                            <th class="border-0 text-light opacity-75">Session</th>
                            <th class="border-0 text-light opacity-75">Assigned Teachers</th>
                            <th class="border-0 text-light opacity-75 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                        <tr>
                            <td class="ps-4 text-white">{{ $subject->name }}</td>
                            <td><span class="badge bg-info">{{ $subject->code }}</span></td>
                            <td class="text-white">{{ $subject->department->name ?? 'N/A' }}</td>
                            <td class="text-white">{{ $subject->semester }}</td>
                            
                            <td class="text-white">
                                @if($subject->teachers->count() > 0)
                                    {{ $subject->teachers->pluck('pivot.session')->unique()->join(', ') }}
                                @else
                                    <span class="text-light opacity-50">N/A</span>
                                @endif
                            </td>

                            <td>
                                @if($subject->teachers->count() > 0)
                                    <span class="badge bg-secondary me-1">
                                        {{ $subject->teachers->pluck('name')->join(', ') }}
                                    </span>
                                @else
                                    <span class="text-light opacity-50">None</span>
                                @endif
                            </td>
                            
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    
                                    <!-- ASSIGN BUTTON -->
                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#assignModal"
                                            data-subject-id="{{ $subject->id }}"
                                            data-subject-name="{{ $subject->name }}"
                                            title="Assign Teacher">
                                        <i class="fas fa-user-plus"></i>
                                    </button>

                                    <!-- EDIT BUTTON -->
                                    <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <!-- DELETE BUTTON -->
                                    <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">
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
    </div>

</div>

<!-- ASSIGN MODAL - GLASS THEME -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-glass">
            <div class="modal-header border-0">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Assign Teacher</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('subjects.assign') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="subject_id" id="modal_subject_id">
                    
                    <!-- Subject Input -->
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" id="modal_subject_name" class="form-control form-control-glass" readonly>
                    </div>

                    <!-- Teacher Select -->
                    <div class="mb-3">
                        <label class="form-label">Select Teacher</label>
                        <select name="teacher_id" class="form-select form-select-glass" required>
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Session Input -->
                    <div class="mb-3">
                        <label class="form-label">Session</label>
                        <input type="text" name="session" class="form-control form-control-glass" placeholder="e.g. 2022-2026" required>
                    </div>
                </div>
                
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i> Assign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var assignModal = document.getElementById('assignModal')
    assignModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget
        document.getElementById('modal_subject_id').value = button.getAttribute('data-subject-id')
        document.getElementById('modal_subject_name').value = button.getAttribute('data-subject-name')
    })
</script>

<style>
    /* GLASS THEME FOR MODAL */
    .modal-glass {
        background: rgba(255, 255, 255, 0.05) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        border-radius: 15px;
        color: #fff;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    }

    .modal-header, .modal-footer {
        background: transparent !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    /* GLASS THEME FOR INPUTS INSIDE MODAL */
    .form-control-glass, .form-select-glass {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
        border-radius: 8px;
    }

    .form-control-glass::placeholder {
        color: rgba(255, 255, 255, 0.5) !important;
    }

    .form-select-glass {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%28255, 255, 255, 0.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    }

    .form-select-glass option {
        background-color: #343a40 !important; /* Darker option for contrast */
        color: #ffffff !important;
        padding: 10px;
    }
</style>
@endsection