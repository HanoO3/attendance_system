@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Edit Student</h2>
            <p class="text-light opacity-75 mb-0">Update student details</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="card border-light shadow">
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('students.update', $student->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Student Name</label>
                                <input type="text" name="student_name" class="form-control bg-transparent text-white border-light" 
                                       value="{{ old('student_name', $student->student_name) }}" required>
                            </div>

                            <!-- Father Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Father Name</label>
                                <input type="text" name="father_name" class="form-control bg-transparent text-white border-light" 
                                       value="{{ old('father_name', $student->father_name) }}" required>
                            </div>

                            <!-- Roll Number -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Roll Number</label>
                                <input type="text" name="roll_number" class="form-control bg-transparent text-white border-light" 
                                       value="{{ old('roll_number', $student->roll_number) }}" readonly>
                            </div>

                            <!-- Contact Number -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Contact Number</label>
                                <input type="text" name="contact_number" maxlength="11" 
                                       class="form-control bg-transparent text-white border-light placeholder-white" 
                                       value="{{ old('contact_number', $student->contact_number) }}" 
                                       placeholder="03XXXXXXXXX" 
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                                       required>
                                
                                @error('contact_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @else
                                    <small class="text-light opacity-50">03XXXXXXXXX (11 Digits)</small>
                                @enderror
                            </div>

                            <!-- Department -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Department</label>
                                <select name="department_id" id="department_select" class="form-select border-light" required>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ $student->department_id == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- UPDATED: Department Code Label -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Department Code</label>
                                <input type="text" name="course" id="course_code" class="form-control bg-transparent text-white border-light" 
                                       value="{{ old('course', $student->course) }}" readonly required>
                                <small class="text-light opacity-50">Automatically generated</small>
                            </div>

                            <!-- Semester -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Semester</label>
                                <select name="semester" class="form-select border-light" required>
                                    @foreach($semesters as $sem)
                                        <option value="{{ $sem }}" {{ $student->semester == $sem ? 'selected' : '' }}>
                                            {{ $sem }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Session -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Session</label>
                                <input type="text" name="session" class="form-control bg-transparent text-white border-light" 
                                       value="{{ old('session', $student->session) }}" placeholder="e.g. 2020-2024" required>
                            </div>

                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save me-2"></i> Update Student
                            </button>
                            <a href="{{ route('students.index') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-arrow-left me-2"></i> Back
                            </a>
                        </div>

                    </form>

                    <style>
                        .form-select { background-color: white !important; color: black !important; }
                        input[readonly] { background-color: rgba(255, 255, 255, 0.1) !important; cursor: not-allowed; }
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

                        var deptSelect = document.getElementById('department_select');
                        var courseField = document.getElementById('course_code');

                        if(deptSelect && courseField) {
                            deptSelect.addEventListener('change', function() {
                                var selectedOption = this.options[this.selectedIndex];
                                courseField.value = generateCourseCode(selectedOption.text);
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection