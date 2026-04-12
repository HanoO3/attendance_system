@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Attendance Report</h2>
            <p class="text-light opacity-75 mb-0">Select your assigned subject to generate report</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-light shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-3" id="reportForm">
                
                <!-- Department -->
                <div class="col-md-2">
                    <label class="form-label text-light opacity-75">Department</label>
                    <select name="department_id" id="filterDept" class="form-select bg-transparent text-white border-light custom-select-dark" required>
                        <option value="" selected disabled>-- Select --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Semester -->
                <div class="col-md-1">
                    <label class="form-label text-light opacity-75">Sem</label>
                    <select name="semester" id="filterSem" class="form-select bg-transparent text-white border-light custom-select-dark" required>
                        <option value="" selected disabled>--</option>
                        @foreach ($semesters as $sem)
                            @php $suffix = ($sem == 1 ? 'st' : ($sem == 2 ? 'nd' : ($sem == 3 ? 'rd' : 'th'))); @endphp
                            <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>
                                {{ $sem . $suffix }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Subject Filter (Dynamic) -->
                <div class="col-md-3">
                    <label class="form-label text-light opacity-75">Subject</label>
                    <select name="subject_id" id="subject_dropdown" class="form-select bg-transparent text-white border-light custom-select-dark" required disabled>
                        <option value="" disabled selected>Select Dept & Sem</option>
                    </select>
                </div>

                <!-- Session -->
                <div class="col-md-2">
                    <label class="form-label text-light opacity-75">Session</label>
                    <input type="text" name="session" id="session_input" value="{{ request('session') }}" 
                           class="form-control bg-transparent text-white border-light placeholder-white" 
                           placeholder="e.g. 2022-2026" required>
                </div>

                <!-- Start Date -->
                <div class="col-md-2">
                    <label class="form-label text-light opacity-75">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control bg-transparent text-white border-light" required>
                </div>

                <!-- End Date -->
                <div class="col-md-2">
                    <label class="form-label text-light opacity-75">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control bg-transparent text-white border-light" required>
                </div>

                <!-- Search Button -->
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Generate
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Report Table -->
    @if($students && count($students) > 0)
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
            Subject: <b>{{ $selectedSubject->name }}</b> 
            <small class="text-light opacity-50 ms-2">Session: {{ request('session') }}</small>
        </h5>
        <a href="{{ route('reports.export', request()->query()) }}" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf me-1"></i> Export PDF
        </a>
    </div>

    <div class="card border-light shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-transparent">
                        <tr>
                            <th class="border-0 text-light opacity-75">Sr#</th>
                            <th class="border-0 text-light opacity-75">Roll No</th>
                            <th class="border-0 text-light opacity-75">Name</th>
                            <th class="border-0 text-light opacity-75 text-center">Total</th>
                            <th class="border-0 text-light opacity-75 text-center">Present</th>
                            <th class="border-0 text-light opacity-75 text-center">Late</th>
                            <th class="border-0 text-light opacity-75 text-center">Leave</th>
                            <th class="border-0 text-light opacity-75 text-center">Absent</th>
                            <th class="border-0 text-light opacity-75 text-center">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->roll_number }}</td>
                            <td>{{ $s->student_name }}</td>
                            <td class="text-center">{{ $s->total_classes }}</td>
                            <td class="text-center"><span class="badge bg-success">{{ $s->present_count }}</span></td>
                            <td class="text-center"><span class="badge bg-warning text-dark">{{ $s->late_count }}</span></td>
                            <td class="text-center"><span class="badge" style="background:#fd7e14">{{ $s->leave_count }}</span></td>
                            <td class="text-center"><span class="badge bg-danger">{{ $s->absent_count }}</span></td>
                            <td class="text-center fw-bold {{ $s->percentage >= 75 ? 'text-success' : 'text-danger' }}">{{ $s->percentage }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @elseif($filter)
    <div class="card border-light shadow">
        <div class="card-body text-center py-5">
            <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
            <h5>No Records Found</h5>
            <p class="text-light opacity-50">Check if the session matches the assigned session for this subject.</p>
        </div>
    </div>
    @endif

</div>

<style>
    .form-select { background-image: url("data:image/svg+xml,..."); }
    .custom-select-dark option { background-color: #ffffff !important; color: #000000 !important; }
</style>

<script>
    const deptDropdown = document.getElementById('filterDept');
    const semDropdown = document.getElementById('filterSem');
    const subjectDropdown = document.getElementById('subject_dropdown');

    function loadSubjects() {
        const deptId = deptDropdown.value;
        const semester = semDropdown.value;
        
        if(deptId && semester) {
            subjectDropdown.disabled = false;
            subjectDropdown.innerHTML = '<option value="">Loading...</option>';

            var urlTemplate = "{{ route('subjects.byDeptSem', ['dept_id' => 'DEPT_ID_PH', 'semester' => 'SEM_PH']) }}";
            var finalUrl = urlTemplate.replace('DEPT_ID_PH', deptId).replace('SEM_PH', semester);

            fetch(finalUrl)
                .then(response => response.json())
                .then(data => {
                    let optionsHtml = '<option value="" disabled selected>-- Select Subject --</option>';
                    
                    if(data.length > 0) {
                        data.forEach(sub => {
                            let selected = "{{ request('subject_id') }}" == sub.id ? 'selected' : '';
                            optionsHtml += `<option value="${sub.id}" ${selected}>${sub.name}</option>`;
                        });
                    } else {
                        optionsHtml = '<option value="" disabled>No Subject Assigned</option>';
                    }
                    
                    subjectDropdown.innerHTML = optionsHtml;
                });
        } else {
            subjectDropdown.disabled = true;
            subjectDropdown.innerHTML = '<option value="" disabled selected>Select Dept & Sem</option>';
        }
    }

    if(deptDropdown && semDropdown) {
        deptDropdown.addEventListener('change', loadSubjects);
        semDropdown.addEventListener('change', loadSubjects);
        
        // Trigger on load if values exist
        if(deptDropdown.value && semDropdown.value) {
            loadSubjects();
        }
    }
</script>
@endsection