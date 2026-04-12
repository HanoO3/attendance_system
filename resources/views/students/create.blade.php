@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Add New Student</h2>
            <p class="text-light opacity-75 mb-0">Register a new student in the system</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="card border-light shadow">
                <div class="card-body">

                    <form method="POST" action="{{ route('students.store') }}">
                        @csrf

                        @if(isset($requestData))
                            <input type="hidden" name="profile_request_id" value="{{ $requestData->id }}">
                        @endif

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label text-white">Student Name</label>
                                <input type="text" name="name" id="name"
                                       class="form-control bg-transparent text-white border-light placeholder-white" 
                                       value="{{ old('name') ?? ($requestData->student_name ?? '') }}" placeholder="Full Name">
                            </div>

                            <!-- Father Name -->
                            <div class="col-md-6 mb-3">
                                <label for="father_name" class="form-label text-white">Father Name</label>
                                <input type="text" name="father_name" id="father_name"
                                       class="form-control bg-transparent text-white border-light placeholder-white" 
                                       value="{{ old('father_name') ?? ($requestData->father_name ?? '') }}" placeholder="Father's Name">
                            </div>

                            <!-- Roll Number -->
                            <div class="col-md-6 mb-3">
                                <label for="roll_number" class="form-label text-white">Roll Number</label>
                                <input type="text" name="roll_number" id="roll_number"
                                       class="form-control bg-transparent text-white border-light placeholder-white" 
                                       value="{{ old('roll_number') ?? ($requestData->roll_no ?? '') }}" placeholder="e.g. 123">
                            </div>

                           <!-- Contact Number -->
                            <div class="col-md-6 mb-3">
                                <label for="contact_number" class="form-label text-white">Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" maxlength="11" 
                                       class="form-control bg-transparent text-white border-light placeholder-white" 
                                       value="{{ old('contact_number') ?? ($requestData->contact_number ?? '') }}" 
                                       placeholder="03XXXXXXXXX"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <small class="text-light opacity-50">Format: 03XXXXXXXXX (11 Digits)</small>
                            </div>

                            <!-- Department -->
                            <div class="col-md-6 mb-3">
                                <label for="department_id" class="form-label text-white">Department</label>
                                <select name="department_id" id="department_id" class="form-select border-light">
                                    <option value="" selected disabled>-- Select Department --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" 
                                            {{ (old('department_id') ?? ($requestData->department_id ?? '')) == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- UPDATED: Department Code Label -->
                            <div class="col-md-6 mb-3">
                                <label for="course" class="form-label text-white">Department Code</label>
                                <input type="text" name="course" id="course" 
                                       class="form-control bg-transparent text-white border-light placeholder-white" 
                                       value="{{ old('course') ?? ($requestData->course ?? '') }}" 
                                       placeholder="Select Department First" readonly>
                                <small class="text-light opacity-50">Automatically generated based on department</small>
                            </div>

                            <!-- Semester -->
                            <div class="col-md-6 mb-3">
                                <label for="semester" class="form-label text-white">Semester</label>
                                <select name="semester" id="semester" class="form-select border-light">
                                    <option value="" selected disabled>-- Select Semester --</option>
                                    @foreach($semesters as $sem)
                                        <option value="{{ $sem }}" 
                                            {{ (old('semester') ?? ($requestData->semester ?? '')) == $sem ? 'selected' : '' }}>
                                            {{ $sem }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Session -->
                            <div class="col-md-6 mb-3">
                                <label for="session" class="form-label text-white">Session</label>
                                <input type="text" name="session" id="session" class="form-control bg-transparent text-white border-light placeholder-white" 
                                       value="{{ old('session', $requestData->session ?? '') }}" placeholder="e.g. 2020-2024">
                            </div>

                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save me-2"></i> Save Student
                            </button>
                            <a href="{{ route('students.index') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-arrow-left me-2"></i> Back
                            </a>
                        </div>

                    </form>

                    <style>
                        .placeholder-white::placeholder {
                            color: #ffffff !important;
                            opacity: 1;
                        }
                        .form-select {
                            background-color: rgba(255, 255, 255, 0.1) !important;
                            color: #ffffff !important;
                        }
                        .form-select option {
                            background-color: #ffffff;
                            color: #000000;
                        }
                        input[readonly] {
                            background-color: rgba(255, 255, 255, 0.05) !important; 
                            cursor: not-allowed; 
                            opacity: 0.9;
                        }
                    </style>

                    <!-- Script with Hash Logic (Matches Model) -->
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
                                var deptName = selectedOption.text;
                                courseField.value = generateCourseCode(deptName);
                            });
                        }

                        document.addEventListener('DOMContentLoaded', function() {
                           if (courseField && !courseField.value) {
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
</div>
@endsection