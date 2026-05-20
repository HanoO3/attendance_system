@extends('layouts.app')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Edit Student</h1>
        <p style="font-size:.82rem;color:#6b7280;margin:0;">Update student details</p>
    </div>
    <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <div class="hico"><i class="fas fa-user-edit"></i></div>
                Student Information
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif
                <form method="POST" action="{{ route('students.update', $student->id) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Student Name</label>
                            <input type="text" name="student_name" class="form-control"
                                   style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                                   value="{{ old('student_name', $student->student_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Father Name</label>
                            <input type="text" name="father_name" class="form-control"
                                   style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                                   value="{{ old('father_name', $student->father_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Roll Number <small class="text-muted">(Cannot be changed)</small></label>
                            <input type="text" name="roll_number" class="form-control"
                                   style="background:#f8fafc;border:1.5px solid #d1d5db;color:#6b7280;cursor:not-allowed;"
                                   value="{{ old('roll_number', $student->roll_number) }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" maxlength="11" class="form-control"
                                   style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                                   value="{{ old('contact_number', $student->contact_number) }}"
                                   placeholder="03XXXXXXXXX"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                            <div class="form-text">Format: 03XXXXXXXXX (11 digits)</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <select name="department_id" id="department_select" class="form-select"
                                    style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ $student->department_id == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department Code</label>
                            <input type="text" name="course" id="course_code" class="form-control"
                                   style="background:#f8fafc;border:1.5px solid #d1d5db;color:#111827;cursor:not-allowed;"
                                   value="{{ old('course', $student->course) }}" readonly>
                            <div class="form-text">Auto-generated from department</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select"
                                    style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
                                @foreach($semesters as $sem)
                                    <option value="{{ $sem }}" {{ $student->semester == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Session</label>
                            <input type="text" name="session" class="form-control"
                                   style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                                   value="{{ old('session', $student->session) }}" placeholder="e.g. 2020-2024" required>
                        </div>
                        <div class="col-12 mt-2">
                            <hr style="border-color:#e5e7eb;">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i> Update Student
                                </button>
                                <a href="{{ route('students.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function generateCourseCode(deptName) {
    if (!deptName) return '';
    let name  = deptName.replace(/Department|of|BS|MS/gi, '').trim();
    let parts = name.split(/\s+/).filter(p => p.length > 0);
    let prefix = parts.length === 1 ? parts[0].substring(0,3).toUpperCase() : parts.map(p => p.charAt(0)).join('').toUpperCase();
    let hash = 0;
    for (let i = 0; i < deptName.length; i++) { hash = ((hash << 5) - hash) + deptName.charCodeAt(i); hash |= 0; }
    return prefix + '-' + (Math.abs(hash) % 900 + 100);
}
var deptSel = document.getElementById('department_select');
var courseF = document.getElementById('course_code');
if (deptSel && courseF) {
    deptSel.addEventListener('change', function() {
        courseF.value = generateCourseCode(this.options[this.selectedIndex].text);
    });
}
</script>
@endsection