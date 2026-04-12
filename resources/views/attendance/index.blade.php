@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Mark Attendance</h2>
            <p class="text-light opacity-75 mb-0">
                @isset($subject)
                    Subject: <b class="text-white">{{ $subject->name }}</b> 
                    ({{ $subject->department->name ?? 'N/A' }} - {{ $subject->semester }})
                @else
                    Filter by department, semester and session
                @endisset
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Card -->
    <div class="card border-light shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.index') }}" class="row g-3" id="filterForm">
                
                <div class="col-md-3">
                    <label class="form-label text-light opacity-75">Department</label>
                    <select name="department_id" id="filterDept" class="form-select bg-transparent text-white border-light custom-select-dark" required>
                        <option value="" selected disabled>-- Select Dept --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ $department_id == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label text-light opacity-75">Semester</label>
                    <select name="semester" id="filterSem" class="form-select bg-transparent text-white border-light custom-select-dark" required>
                        <option value="" selected disabled>-- Select --</option>
                        @foreach ($semesters as $sem)
                            {{-- Logic to show 1st, 2nd, 3rd instead of 1, 2, 3 --}}
                            @php
                                $suffix = ($sem == 1 ? 'st' : ($sem == 2 ? 'nd' : ($sem == 3 ? 'rd' : 'th')));
                            @endphp
                            <option value="{{ $sem }}" {{ $semester == $sem ? 'selected' : '' }}>
                                {{ $sem . $suffix }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label text-light opacity-75">Session</label>
                    <input type="text" name="session" id="filterSession" value="{{ $session ?? '' }}" 
                           class="form-control bg-transparent text-white border-light" placeholder="e.g. 2022-2026" required>
                </div>

                <!-- Subject Dropdown (Hidden Initially, Filled via AJAX) -->
                <div class="col-md-3 d-none" id="subject_dropdown_container">
                    <label class="form-label text-light opacity-75">Subject</label>
                    <select name="subject_id" id="subject_dropdown" class="form-select bg-transparent text-white border-light custom-select-dark">
                        <option value="">Loading...</option>
                    </select>
                </div>

                <!-- Common: Date -->
                <div class="col-md-2">
                    <label class="form-label text-light opacity-75">Date</label>
                    <input type="date" name="date" value="{{ $date ?? date('Y-m-d') }}" class="form-control bg-transparent text-white border-light" required>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Student List -->
    @if($students && count($students) > 0)
    <div class="card border-light shadow">
        <div class="card-header bg-transparent border-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    @isset($subject)
                        Subject: <b>{{ $subject->name }}</b>
                    @else
                        Dept: <b>{{ $departments->find($department_id)->name ?? 'All' }}</b> | Sem: <b>{{ $semester ?? 'All' }}</b>
                    @endisset
                    | Session: <b>{{ $session ?? 'N/A' }}</b>
                </h5>
                <span class="text-light opacity-75">Date: {{ $date ?? date('Y-m-d') }}</span>
            </div>
        </div>
        
        <div class="card-body p-0">
            <form method="POST" action="{{ route('attendance.store') }}">
                @csrf
                
                <input type="hidden" name="department_id" value="{{ $department_id ?? '' }}">
                <input type="hidden" name="attendance_date" value="{{ $date ?? date('Y-m-d') }}">
                <input type="hidden" name="session" value="{{ $session ?? '' }}">
                <input type="hidden" name="subject_id" value="{{ request('subject_id') ?? '' }}">

                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" id="attendanceTable">
                        <thead class="bg-transparent">
                            <tr>
                                <th class="border-0 text-light opacity-75">Sr#</th>
                                <th class="border-0 text-light opacity-75">Roll No</th>
                                <th class="border-0 text-light opacity-75">Student Name</th>
                                <th class="border-0 text-light opacity-75">Status</th>
                                <th class="border-0 text-light opacity-75">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $student->roll_number }}</td>
                                <td>{{ $student->student_name }}</td>
                                <td>
                                    @php
                                        $existing = $attendanceData[$student->id] ?? null;
                                        $currentStatus = $existing ? $existing->status : 'present';
                                    @endphp

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" 
                                               name="status[{{ $student->id }}]" value="present" 
                                               id="p{{ $student->id }}" 
                                               {{ $currentStatus == 'present' ? 'checked' : '' }}>
                                        <label class="form-check-label text-success" for="p{{ $student->id }}">Present</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" 
                                               name="status[{{ $student->id }}]" value="absent" 
                                               id="a{{ $student->id }}" 
                                               {{ $currentStatus == 'absent' ? 'checked' : '' }}>
                                        <label class="form-check-label text-danger" for="a{{ $student->id }}">Absent</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" 
                                               name="status[{{ $student->id }}]" value="late" 
                                               id="l{{ $student->id }}" 
                                               {{ $currentStatus == 'late' ? 'checked' : '' }}>
                                        <label class="form-check-label text-warning" for="l{{ $student->id }}">Late</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" 
                                               name="status[{{ $student->id }}]" value="leave" 
                                               id="lv{{ $student->id }}" 
                                               {{ $currentStatus == 'leave' ? 'checked' : '' }}>
                                        <label class="form-check-label text-orange" for="lv{{ $student->id }}">Leave</label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="remarks[{{ $student->id }}]" 
                                           class="form-control form-control-sm bg-transparent text-white border-light" 
                                           value="{{ $existing->remarks ?? '' }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-3 border-top border-light">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i> Save Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>

    @elseif(request()->has('subject_id') || request()->has('department_id'))
    <div class="card border-light shadow">
        <div class="card-body text-center py-5">
            <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
            <h5>No Students Found</h5>
            <p class="text-light opacity-50">Check if Session matches exactly (e.g. 2022-2026).</p>
        </div>
    </div>
    @endif

</div>

<style>
    .form-select { background-image: url("data:image/svg+xml,..."); }
    #attendanceTable tbody tr:hover { background-color: rgba(255,255,255,0.05) !important; }
    .custom-select-dark option { background-color: #ffffff !important; color: #000000 !important; }
    .text-orange { color: #fd7e14 !important; }
</style>

<script>
    const deptDropdown = document.getElementById('filterDept');
    const semDropdown = document.getElementById('filterSem');
    const subjectContainer = document.getElementById('subject_dropdown_container');
    const subjectDropdown = document.getElementById('subject_dropdown');

    function loadSubjects() {
        const deptId = deptDropdown.value;
        const semester = semDropdown.value;
        
        // Clear previous
        if(subjectDropdown) subjectDropdown.innerHTML = '<option value="">Loading...</option>';
        
        if(deptId && semester) {
            // Show container IMMEDIATELY (Fix for dropdown not showing)
            subjectContainer.classList.remove('d-none');

            // Generate URL
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
                })
                .catch(error => {
                    console.error('Error:', error);
                    subjectDropdown.innerHTML = '<option value="">Error loading</option>';
                });
        } else {
            subjectContainer.classList.add('d-none');
        }
    }

    // Attach Events
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