@extends('layouts.app')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Attendance Reports</h1>
    <p style="font-size:.82rem;color:#6b7280;margin:0;">Select filters to generate detailed attendance report</p>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-header">
        <div class="hico"><i class="fas fa-filter"></i></div>
        Generate Report
    </div>
    <div class="card-body p-3" style="background:#f8fafc;border-radius:0 0 12px 12px;">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3" id="reportForm">

            <div class="col-md-2">
                <label class="form-label">Department</label>
                <select name="department_id" id="filterDept" class="form-select" style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
                    <option value="" selected disabled>-- Select --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-1">
                <label class="form-label">Semester</label>
                <select name="semester" id="filterSem" class="form-select" style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
                    <option value="" selected disabled>--</option>
                    @foreach ($semesters as $sem)
                        <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>
                            {{ $sem }} Semester
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Subject</label>
                <select name="subject_id" id="subject_dropdown" class="form-select" style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required disabled>
                    <option value="" disabled selected>Select Dept &amp; Sem first</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Session</label>
                <input type="text" name="session" id="session_input" value="{{ request('session') }}"
                       class="form-control"
                       style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                       placeholder="e.g. 2022-2026" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                       class="form-control"
                       style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                       class="form-control"
                       style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-chart-bar me-2"></i> Generate Report
                </button>
            </div>

        </form>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger mb-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Report Table -->
@if($students && count($students) > 0)

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:14px;">
    <div>
        <span style="font-weight:700;color:#111827;">{{ $selectedSubject->name }}</span>
        <span style="font-size:.82rem;color:#6b7280;margin-left:8px;">Session: {{ request('session') }}</span>
    </div>
    <a href="{{ route('reports.export', request()->query()) }}" class="btn btn-danger btn-sm">
        <i class="fas fa-file-pdf me-1"></i> Export PDF
    </a>
</div>

<div class="card">
    <div class="card-header">
        <div class="hico"><i class="fas fa-chart-bar"></i></div>
        Attendance Summary
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Present</th>
                        <th class="text-center">Late</th>
                        <th class="text-center">Leave</th>
                        <th class="text-center">Absent</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $s->roll_number }}</span></td>
                        <td style="font-weight:600;">{{ $s->student_name }}</td>
                        <td class="text-center">{{ $s->total_classes }}</td>
                        <td class="text-center"><span class="badge bg-success">{{ $s->present_count }}</span></td>
                        <td class="text-center"><span class="badge bg-warning text-dark">{{ $s->late_count }}</span></td>
                        <td class="text-center"><span class="badge" style="background:#fd7e14;color:#fff;">{{ $s->leave_count }}</span></td>
                        <td class="text-center"><span class="badge bg-danger">{{ $s->absent_count }}</span></td>
                        <td class="text-center fw-bold {{ $s->percentage >= 75 ? 'text-success' : 'text-danger' }}">{{ $s->percentage }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@elseif(isset($filter) && $filter)
<div class="card">
    <div class="card-body text-center py-5" style="color:#9ca3af;">
        <i class="fas fa-folder-open fa-3x mb-3 d-block opacity-50"></i>
        <h5>No Records Found</h5>
        <p>Check if the session matches the assigned session for this subject.</p>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
const deptDropdown    = document.getElementById('filterDept');
const semDropdown     = document.getElementById('filterSem');
const subjectDropdown = document.getElementById('subject_dropdown');

function loadSubjects() {
    const deptId   = deptDropdown.value;
    const semester = semDropdown.value;
    if (deptId && semester) {
        subjectDropdown.disabled = false;
        subjectDropdown.innerHTML = '<option value="">Loading...</option>';
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
            });
    } else {
        subjectDropdown.disabled = true;
        subjectDropdown.innerHTML = '<option value="" disabled selected>Select Dept &amp; Sem first</option>';
    }
}

if (deptDropdown && semDropdown) {
    deptDropdown.addEventListener('change', loadSubjects);
    semDropdown.addEventListener('change', loadSubjects);
    if (deptDropdown.value && semDropdown.value) loadSubjects();
}
</script>
@endsection