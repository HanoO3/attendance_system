@extends('layouts.app')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Student Management</h1>
        <p style="font-size:.82rem;color:#6b7280;margin:0;">Filter, view and manage all student records</p>
    </div>
    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus me-2"></i> Add New Student
    </a>
</div>

<!-- Filter Card -->
<div class="card mb-4" style="border:1.5px solid #e5e7eb;">
    <div class="card-header">
        <div class="hico"><i class="fas fa-filter"></i></div>
        Filter Students
    </div>
    <div class="card-body p-3" style="background:#f8fafc;border-radius:0 0 12px 12px;">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Department</label>
                <select id="filterDept" class="form-select" style="background:#fff;border:1.5px solid #d1d5db;color:#111827;">
                    <option value="all">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Semester</label>
                <select id="filterSem" class="form-select" style="background:#fff;border:1.5px solid #d1d5db;color:#111827;">
                    <option value="all">All Semesters</option>
                    @foreach(['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'] as $sem)
                        <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>
                            {{ $sem }} Semester
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Session</label>
                <input type="text" id="filterSession" value="{{ request('session') }}"
                       class="form-control"
                       style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                       placeholder="e.g. 2020-2024">
            </div>
        </div>
    </div>
</div>

<!-- Students Table -->
<div class="card">
    <div class="card-header">
        <div class="hico"><i class="fas fa-user-graduate"></i></div>
        All Students
    </div>
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success m-3 mb-0">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table mb-0" id="studentTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th>Father Name</th>
                        <th>Department</th>
                        <th>Semester</th>
                        <th>Session</th>
                        <th>Contact</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr class="student-row"
                        data-dept="{{ $student->department_id }}"
                        data-sem="{{ $student->semester }}"
                        data-sess="{{ $student->session }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $student->roll_number }}</span></td>
                        <td style="font-weight:600;">{{ $student->student_name }}</td>
                        <td style="color:#6b7280;">{{ $student->father_name }}</td>
                        <td>{{ $student->department->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info fw-bold">{{ $student->semester }}</span></td>
                        <td style="color:#6b7280;">{{ $student->session ?? 'N/A' }}</td>
                        <td>{{ $student->contact_number }}</td>
                        <td class="text-center">
                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-outline-info me-1" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('students.destroy', $student->id) }}" style="display:inline;" onsubmit="return confirm('Delete this student?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="9" class="text-center py-5" style="color:#9ca3af;">
                            <i class="fas fa-users fa-3x mb-3 d-block opacity-50"></i>
                            No students found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div id="noResults" class="text-center py-5 d-none" style="color:#9ca3af;">
            <i class="fas fa-search fa-2x mb-2 d-block"></i>
            No matching records found.
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deptSelect = document.getElementById('filterDept');
    const semSelect  = document.getElementById('filterSem');
    const sessInput  = document.getElementById('filterSession');
    const rows       = document.querySelectorAll('.student-row');
    const noResults  = document.getElementById('noResults');
    const emptyRow   = document.getElementById('emptyRow');

    function filterTable() {
        const deptVal = deptSelect.value;
        const semVal  = semSelect.value;
        const sessVal = sessInput.value.trim().toLowerCase();
        let visible   = 0;

        rows.forEach(function(row) {
            var matchDept = deptVal === 'all' || deptVal === row.dataset.dept;
            var matchSem  = semVal  === 'all' || semVal  === row.dataset.sem;
            var matchSess = sessVal === ''    || (row.dataset.sess || '').toLowerCase().includes(sessVal);
            var show = matchDept && matchSem && matchSess;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (noResults) {
            if (visible === 0 && !emptyRow) {
                noResults.classList.remove('d-none');
            } else {
                noResults.classList.add('d-none');
            }
        }
    }

    deptSelect.addEventListener('change', filterTable);
    semSelect.addEventListener('change', filterTable);
    sessInput.addEventListener('input', filterTable);
});
</script>
@endsectiond