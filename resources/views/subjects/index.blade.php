@extends('layouts.app')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Subjects Management</h1>
        <p style="font-size:.82rem;color:#6b7280;margin:0;">Manage all subjects and assign teachers</p>
    </div>
    <a href="{{ route('subjects.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add Subject
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div class="hico"><i class="fas fa-book-open"></i></div>
        All Subjects
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Department</th>
                        <th>Semester</th>
                        <th>Session</th>
                        <th>Assigned Teacher</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="font-weight:600;">{{ $subject->name }}</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info fw-bold">{{ $subject->code }}</span></td>
                        <td>{{ $subject->department->name ?? 'N/A' }}</td>
                        <td>{{ $subject->semester }}</td>
                        <td style="color:#6b7280;">
                            @if($subject->teachers->count() > 0)
                                {{ $subject->teachers->pluck('pivot.session')->unique()->join(', ') }}
                            @else
                                <span style="color:#9ca3af;">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($subject->teachers->count() > 0)
                                <span class="badge bg-secondary bg-opacity-10 text-secondary fw-semibold">
                                    {{ $subject->teachers->pluck('name')->join(', ') }}
                                </span>
                            @else
                                <span style="color:#9ca3af;">None</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <button type="button" class="btn btn-sm btn-outline-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#assignModal"
                                        data-subject-id="{{ $subject->id }}"
                                        data-subject-name="{{ $subject->name }}"
                                        title="Assign Teacher">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                                <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this subject?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5" style="color:#9ca3af;">
                            <i class="fas fa-book fa-3x mb-3 d-block opacity-50"></i>
                            No subjects found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ASSIGN TEACHER MODAL -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight:700;font-size:.95rem;">
                    <i class="fas fa-user-plus me-2 text-success"></i> Assign Teacher
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('subjects.assign') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="subject_id" id="modal_subject_id">

                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" id="modal_subject_name" class="form-control" readonly style="background:#f8fafc;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Teacher</label>
                        <select name="teacher_id" class="form-select" required>
                            <option value="">-- Select Teacher --</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Session</label>
                        <input type="text" name="session" class="form-control" placeholder="e.g. 2022-2026" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-check me-1"></i> Assign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('assignModal').addEventListener('show.bs.modal', function(event) {
    var btn = event.relatedTarget;
    document.getElementById('modal_subject_id').value   = btn.getAttribute('data-subject-id');
    document.getElementById('modal_subject_name').value = btn.getAttribute('data-subject-name');
});
</script>
@endsection