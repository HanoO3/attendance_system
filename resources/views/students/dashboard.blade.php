@extends('layouts.app')

@section('content')

<div class="page-hdr">
    <div class="page-hdr-left">
        <h1>Student Dashboard</h1>
        <p>Welcome back, <strong>{{ $student->student_name ?? auth()->user()->name }}</strong></p>
    </div>
</div>

@if($student)

<!-- Profile Card -->
<div class="card mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div style="width:54px;height:54px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:14px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;flex-shrink:0;">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <h4 class="mb-0" style="font-weight:800;color:#111827;">{{ $student->student_name }}</h4>
                <span style="font-size:.82rem;color:#6b7280;">{{ $student->department->name ?? 'N/A' }}</span>
            </div>
        </div>
        <hr style="border-color:#e5e7eb;">
        <div class="row text-center g-2">
            <div class="col-6 col-md-3">
                <div style="font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:4px;">Roll No</div>
                <div style="font-size:1rem;font-weight:800;color:#111827;">{{ $student->roll_number }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div style="font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:4px;">Semester</div>
                <div style="font-size:1rem;font-weight:800;color:#111827;">{{ $student->semester }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div style="font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:4px;">Session</div>
                <div style="font-size:1rem;font-weight:800;color:#111827;">{{ $student->session }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div style="font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:4px;">Dept. Code</div>
                <div style="font-size:1rem;font-weight:800;color:#111827;">{{ $student->course }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Stats -->
<div class="row g-3 mb-2">
    <div class="col-xl-3 col-sm-6">
        <a href="{{ route('student.attendance') }}" class="stat-card">
            <div class="st-ico green"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="st-lbl">Present</div>
                <div class="st-val">{{ $presentCount ?? 0 }}</div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-sm-6">
        <a href="{{ route('student.attendance') }}" class="stat-card">
            <div class="st-ico rose"><i class="fas fa-times-circle"></i></div>
            <div>
                <div class="st-lbl">Absent</div>
                <div class="st-val">{{ $absentCount ?? 0 }}</div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-sm-6">
        <a href="{{ route('student.attendance') }}" class="stat-card">
            <div class="st-ico amber"><i class="fas fa-clock"></i></div>
            <div>
                <div class="st-lbl">Late</div>
                <div class="st-val">{{ $lateCount ?? 0 }}</div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-sm-6">
        <a href="{{ route('student.attendance') }}" class="stat-card">
            <div class="st-ico cyan"><i class="fas fa-calendar-minus"></i></div>
            <div>
                <div class="st-lbl">Leave</div>
                <div class="st-val">{{ $leaveCount ?? 0 }}</div>
            </div>
        </a>
    </div>
</div>

@else

<!-- Profile Setup Form -->
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <div class="hico"><i class="fas fa-id-card"></i></div>
                Request Profile Setup
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info mb-4" style="border-radius:10px;border:none;background:rgba(99,102,241,.08);color:#4f46e5;font-size:.855rem;">
                    <i class="fas fa-info-circle me-2"></i>
                    Please fill your details carefully. Admin will approve your profile based on this information.
                </div>

                <form action="{{ route('student.request') }}" method="POST">
                    @csrf
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="student_name" class="form-control" value="{{ old('student_name') }}" placeholder="Full Name" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Father's Name</label>
                            <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}" placeholder="Father's Name" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Roll Number</label>
                            <input type="text" name="roll_no" class="form-control" value="{{ old('roll_no') }}" placeholder="e.g. 123" required>
                            <div class="form-text">This will be your Login ID</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" maxlength="11"
                                   class="form-control"
                                   value="{{ old('contact_number') }}"
                                   placeholder="03XXXXXXXXX"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                            <div class="form-text">03XXXXXXXXX (11 Digits)</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <select name="department_id" id="department_id" class="form-select" required>
                                <option value="" selected disabled>-- Select Department --</option>
                                @foreach(\App\Models\Department::all() as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Department Code</label>
                            <input type="text" name="course" id="course"
                                   class="form-control"
                                   value="{{ old('course') }}"
                                   placeholder="Auto-generated after selecting dept"
                                   readonly style="background:#f8fafc;cursor:not-allowed;">
                            <div class="form-text">Automatically generated</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="" selected disabled>-- Select Semester --</option>
                                @foreach(['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'] as $sem)
                                    <option value="{{ $sem }}" {{ old('semester') == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Session</label>
                            <input type="text" name="session" class="form-control" value="{{ old('session') }}" placeholder="e.g. 2020-2024" required>
                        </div>

                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-primary w-100" style="padding:12px;font-size:.95rem;">
                                <i class="fas fa-paper-plane me-2"></i> Send Request
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endif

@endsection

@section('scripts')
<script>
var deptSelect  = document.getElementById('department_id');
var courseField = document.getElementById('course');

function generateCourseCode(deptName) {
    if (!deptName) return '';
    let name  = deptName.replace(/Department|of|BS|MS/gi, '').trim();
    let parts = name.split(/\s+/).filter(p => p.length > 0);
    let prefix = parts.length === 1 ? parts[0].substring(0,3).toUpperCase() : parts.map(p => p.charAt(0)).join('').toUpperCase();
    let hash = 0;
    for (let i = 0; i < deptName.length; i++) { hash = ((hash << 5) - hash) + deptName.charCodeAt(i); hash |= 0; }
    return prefix + '-' + (Math.abs(hash) % 900 + 100);
}

if (deptSelect && courseField) {
    deptSelect.addEventListener('change', function() {
        courseField.value = generateCourseCode(this.options[this.selectedIndex].text);
    });
}
</script>
@endsection