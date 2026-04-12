@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Student Dashboard</h2>
            <p class="text-light opacity-75 mb-0">Welcome, {{ $student->student_name ?? 'Student' }}</p>
        </div>
    </div>

    @if($student)
    
    <!-- Row 1: ID Details (Profile Card) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-light shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-id-card fa-3x text-white-50"></i>
                        </div>
                        <div class="col">
                            <h4 class="text-white mb-1">{{ $student->student_name }}</h4>
                            <p class="text-light opacity-75 mb-0">{{ $student->department->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <hr class="border-light">
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-2">
                            <small class="text-light opacity-75 d-block">Roll No</small>
                            <h5 class="text-white mb-0">{{ $student->roll_number }}</h5>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <small class="text-light opacity-75 d-block">Semester</small>
                            <h5 class="text-white mb-0">{{ $student->semester }}</h5>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <small class="text-light opacity-75 d-block">Session</small>
                            <h5 class="text-white mb-0">{{ $student->session }}</h5>
                        </div>
                        <!-- UPDATED: Label changed to Dept. Code for clarity -->
                        <div class="col-md-3 col-6 mb-2">
                            <small class="text-light opacity-75 d-block">Dept. Code</small>
                            <h5 class="text-white mb-0">{{ $student->course }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Attendance Stats -->
    <div class="row">
        <!-- Present -->
        <div class="col-xl-3 col-md-6 mb-3">
            <a href="{{ route('student.attendance') }}" class="text-decoration-none">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #28a745;">
                    <div class="card-body py-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #28a745;">Present</div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $presentCount ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x" style="color: #28a745;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Absent -->
        <div class="col-xl-3 col-md-6 mb-3">
            <a href="{{ route('student.attendance') }}" class="text-decoration-none">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #dc3545;">
                    <div class="card-body py-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #dc3545;">Absent</div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $absentCount ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x" style="color: #dc3545;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Late -->
        <div class="col-xl-3 col-md-6 mb-3">
            <a href="{{ route('student.attendance') }}" class="text-decoration-none">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #FFC107;">
                    <div class="card-body py-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #FFC107;">Late</div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $lateCount ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x" style="color: #FFC107;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Leave -->
        <div class="col-xl-3 col-md-6 mb-3">
            <a href="{{ route('student.attendance') }}" class="text-decoration-none">
                <div class="card shadow h-100 py-2" style="border-left: 4px solid #fd7e14;">
                    <div class="card-body py-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #fd7e14;">Leave</div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $leaveCount ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-minus fa-2x" style="color: #fd7e14;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

@else

    <!-- Profile Not Setup Form -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-light shadow">
                <div class="card-header">
                    <h5 class="mb-0">Request Profile Setup</h5>
                </div>
                <div class="card-body">
                    <p class="text-light opacity-75 mb-4">
                        Please fill your details carefully. Admin will approve your profile based on this information.
                    </p>

                    <form action="{{ route('student.request') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Your Name</label>
                                <input type="text" name="student_name" class="form-control bg-transparent text-white border-light placeholder-white" value="{{ old('student_name') }}" placeholder="Full Name">
                            </div>
                            
                            <!-- Father Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Father's Name</label>
                                <input type="text" name="father_name" class="form-control bg-transparent text-white border-light placeholder-white" value="{{ old('father_name') }}" placeholder="Father's Name">
                            </div>

                            <!-- Roll No -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Roll Number</label>
                                <input type="text" name="roll_no" class="form-control bg-transparent text-white border-light placeholder-white" value="{{ old('roll_no') }}" placeholder="e.g. 123">
                                <small class="text-light opacity-50">This will be your Login ID</small>
                            </div>

                            <!-- Contact Number -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Contact Number</label>
                                <input type="text" name="contact_number" maxlength="11" 
                                       class="form-control bg-transparent text-white border-light placeholder-white" 
                                       value="{{ old('contact_number') }}" 
                                       placeholder="03XXXXXXXXX" 
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <small class="text-light opacity-50">03XXXXXXXXX (11 Digits)</small>
                            </div>

                            <!-- Department -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Department</label>
                                <select name="department_id" id="department_id" class="form-select border-light">
                                    <option value="" selected disabled>-- Select Department --</option>
                                    @foreach(\App\Models\Department::all() as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- UPDATED: Department Code Label -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Department Code</label>
                                <input type="text" name="course" id="course" 
                                       class="form-control bg-transparent text-white border-light placeholder-white" 
                                       value="{{ old('course') }}" 
                                       placeholder="Select Department First" readonly>
                                <small class="text-light opacity-50">Automatically generated</small>
                            </div>

                            <!-- Semester -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Semester</label>
                                <select name="semester" class="form-select border-light">
                                    <option value="" selected disabled>-- Select Semester --</option>
                                    @foreach(['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'] as $sem)
                                        <option value="{{ $sem }}" {{ old('semester') == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Session -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Session</label>
                                <input type="text" name="session" class="form-control bg-transparent text-white border-light placeholder-white" value="{{ old('session') }}" placeholder="e.g. 2020-2024">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-2">
                            <i class="fas fa-paper-plane me-2"></i> Send Request
                        </button>
                    </form>

                    <style>
                        .placeholder-white::placeholder { color: #ffffff !important; opacity: 0.75; }
                        .form-select { background-color: rgba(255,255,255,0.1) !important; color: #ffffff !important; }
                        .form-select option { background-color: #ffffff !important; color: #000000 !important; }
                        input[readonly] { background-color: rgba(255,255,255,0.05) !important; cursor: not-allowed; }
                    </style>

                    <script>
                        function generateCourseCode(deptName) {
                            if (!deptName) return '';

                            let name = deptName.replace(/Department|of|BS|MS/gi, '').trim();
                            let parts = name.split(/\s+/).filter(p => p.length > 0);
                            
                            let prefix = "";

                            if (parts.length === 1) {
                                prefix = parts[0].substring(0, 3).toUpperCase();
                            } else {
                                prefix = parts.map(p => p.charAt(0)).join('').toUpperCase();
                            }

                            let hash = 0;
                            for (let i = 0; i < deptName.length; i++) {
                                hash = ((hash << 5) - hash) + deptName.charCodeAt(i);
                                hash |= 0;
                            }
                            let suffix = Math.abs(hash) % 900 + 100;

                            return prefix + "-" + suffix;
                        }

                        var deptSelect = document.getElementById('department_id');
                        var courseField = document.getElementById('course');

                        if(deptSelect && courseField) {
                            deptSelect.addEventListener('change', function() {
                                var selectedOption = this.options[this.selectedIndex];
                                courseField.value = generateCourseCode(selectedOption.text);
                            });
                        }
                        
                        document.addEventListener('DOMContentLoaded', function() {
                           if (deptSelect && !courseField.value) {
                                var selectedOption = deptSelect.options[deptSelect.selectedIndex];
                                if(selectedOption) {
                                    courseField.value = generateCourseCode(selectedOption.text);
                                }
                           }
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>

@endif

</div>
@endsection