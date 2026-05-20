@extends('layouts.app')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Mark Attendance</h1>
    <p style="font-size:.82rem;color:#6b7280;margin:0;">
        @isset($subject)
            Subject: <strong>{{ $subject->name }}</strong> ({{ $subject->department->name ?? 'N/A' }} - {{ $subject->semester }})
        @else
            Filter by department, semester and session
        @endisset
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-header">
        <div class="hico"><i class="fas fa-filter"></i></div>
        Filter Students
    </div>
    <div class="card-body p-3" style="background:#f8fafc;border-radius:0 0 12px 12px;">
        <form method="GET" action="{{ route('attendance.index') }}" class="row g-3" id="filterForm">

            <div class="col-md-3">
                <label class="form-label">Department</label>
                <select name="department_id" id="filterDept" class="form-select" style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
                    <option value="" selected disabled>-- Select Department --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $department_id == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Semester</label>
                <select name="semester" id="filterSem" class="form-select" style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
                    <option value="" selected disabled>-- Select --</option>
                    @foreach ($semesters as $sem)
                        <option value="{{ $sem }}" {{ $semester == $sem ? 'selected' : '' }}>
                            {{ $sem }} Semester
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Session</label>
                <input type="text" name="session" id="filterSession" value="{{ $session ?? '' }}"
                       class="form-control"
                       style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                       placeholder="e.g. 2022-2026" required>
            </div>

            <div class="col-md-3 d-none" id="subject_dropdown_container">
                <label class="form-label">Subject</label>
                <select name="subject_id" id="subject_dropdown" class="form-select" style="background:#fff;border:1.5px solid #d1d5db;color:#111827;">
                    <option value="">Loading...</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Date</label>
                <input type="date" name="date" value="{{ $date ?? date('Y-m-d') }}"
                       class="form-control"
                       style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
            </div>

            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>

        </form>
    </div>
</div>

<!-- Student Attendance Table -->
@if($students && count($students) > 0)
<div class="card">
    <div class="card-header">
        <div class="hico"><i class="fas fa-clipboard-check"></i></div>
        <span>
            @isset($subject)
                Subject: <strong>{{ $subject->name }}</strong>
            @else
                Dept: <strong>{{ $departments->find($department_id)->name ?? 'All' }}</strong> | Sem: <strong>{{ $semester ?? 'All' }}</strong>
            @endisset
            &nbsp;&mdash;&nbsp; Session: <strong>{{ $session ?? 'N/A' }}</strong>
        </span>
        <span class="ms-auto" style="font-size:.8rem;color:#6b7280;">Date: {{ $date ?? date('Y-m-d') }}</span>
    </div>
    <div class="card-body p-0">
        <form method="POST" action="{{ route('attendance.store') }}">
            @csrf
            <input type="hidden" name="department_id"   value="{{ $department_id ?? '' }}">
            <input type="hidden" name="attendance_date" value="{{ $date ?? date('Y-m-d') }}">
            <input type="hidden" name="session"         value="{{ $session ?? '' }}">
            <input type="hidden" name="subject_id"      value="{{ request('subject_id') ?? '' }}">

            <div class="table-responsive">
                <table class="table mb-0" id="attendanceTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Roll No</th>
                            <th>Student Name</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        @php
                            $existing = $attendanceData[$student->id] ?? null;
                            $currentStatus = $existing ? $existing->status : 'present';
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $student->roll_number }}</span></td>
                            <td style="font-weight:600;">{{ $student->student_name }}</td>
                            <td>
                                <div class="d-flex gap-3 flex-wrap">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="status[{{ $student->id }}]" value="present"
                                               id="p{{ $student->id }}"
                                               {{ $currentStatus == 'present' ? 'checked' : '' }}>
                                        <label class="form-check-label text-success fw-semibold" for="p{{ $student->id }}">Present</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="status[{{ $student->id }}]" value="absent"
                                               id="a{{ $student->id }}"
                                               {{ $currentStatus == 'absent' ? 'checked' : '' }}>
                                        <label class="form-check-label text-danger fw-semibold" for="a{{ $student->id }}">Absent</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="status[{{ $student->id }}]" value="late"
                                               id="l{{ $student->id }}"
                                               {{ $currentStatus == 'late' ? 'checked' : '' }}>
                                        <label class="form-check-label text-warning fw-semibold" for="l{{ $student->id }}">Late</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="status[{{ $student->id }}]" value="leave"
                                               id="lv{{ $student->id }}"
                                               {{ $currentStatus == 'leave' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" style="color:#fd7e14;" for="lv{{ $student->id }}">Leave</label>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="remarks[{{ $student->id }}]"
                                       class="form-control form-control-sm"
                                       style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                                       value="{{ $existing->remarks ?? '' }}"
                                       placeholder="Optional remark">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3" style="border-top:1px solid #e5e7eb;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i> Save Attendance
                </button>
            </div>
        </form>
    </div>
</div>

@elseif(request()->has('subject_id') || request()->has('department_id'))
<div class="card">
    <div class="card-body text-center py-5" style="color:#9ca3af;">
        <i class="fas fa-users fa-3x mb-3 d-block opacity-50"></i>
        <h5>No Students Found</h5>
        <p>Check if Session matches exactly (e.g. 2022-2026).</p>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
const deptDropdown    = document.getElementById('filterDept');
const semDropdown     = document.getElementById('filterSem');
const subjectCont     = document.getElementById('subject_dropdown_container');
const subjectDropdown = document.getElementById('subject_dropdown');

function loadSubjects() {
    const deptId   = deptDropdown.value;
    const semester = semDropdown.value;
    if (subjectDropdown) subjectDropdown.innerHTML = '<option value="">Loading...</option>';
    if (deptId && semester) {
        subjectCont.classList.remove('d-none');
        var urlTemplate = "{{ route('subjects.byDeptSem', ['dept_id' => 'DEPT_ID_PH', 'semester' => 'SEM_PH']) }}";
        var finalUrl = urlTemplate.replace('DEPT_ID_PH', deptId).replace('SEM_PH', semester);
        fetch(finalUrl)
            .then(r => r.json())
            .then(data => {
                let html = '<option value="" disabled selected>-- Select Subject --</option>';
                if (data.length > 0) {
                    data.forEach(sub => {
                        let sel = "{{ request('subject_id') }}" == sub.id ? 'selected' : '';
                        html += `<option value="${sub.id}" ${sel}>${sub.name}</option>`;
                    });
                } else {
                    html = '<option value="" disabled>No Subject Assigned</option>';
                }
                subjectDropdown.innerHTML = html;
            })
            .catch(() => { subjectDropdown.innerHTML = '<option value="">Error loading</option>'; });
    } else {
        subjectCont.classList.add('d-none');
    }
}

if (deptDropdown && semDropdown) {
    deptDropdown.addEventListener('change', loadSubjects);
    semDropdown.addEventListener('change', loadSubjects);

    // Auto load subjects on page load if values already present
    if (deptDropdown.value && semDropdown.value) {
        loadSubjects();
    }
}

// Auto submit form when subject is selected
document.addEventListener('change', function(e) {
    if (e.target && e.target.id === 'subject_dropdown') {
        if (e.target.value) {
            document.getElementById('filterForm').submit();
        }
    }
});
</script>
@endsection