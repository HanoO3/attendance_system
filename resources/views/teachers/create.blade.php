@extends('layouts.app')

@section('content')
<div>
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Add New Teacher</h2>
            <p class="text-muted mb-0">Register a new teacher in the system</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-body">
                    
                    <!-- Error List (Top Par) -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('teachers.store') }}">
                        @csrf

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-dark">Full Name</label>
                                <input type="text" name="name" 
                                       class="form-control  " 
                                       value="{{ old('name') }}" placeholder="Enter Name" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-dark">Email Address</label>
                                <input type="email" name="email" 
                                       class="form-control  " 
                                       value="{{ old('email') }}" placeholder="teacher@example.com" required>
                            </div>

                            <!-- Contact Number (Server Validation Only) -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-dark">Contact Number</label>
                                <input type="text" name="contact" 
                                       class="form-control  " 
                                       value="{{ old('contact') }}" 
                                       placeholder="03XXXXXXXXX" 
                                       maxlength="11" 
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                
                                <!-- Inline Error Message -->
                                @error('contact')
                                    <small class="text-danger font-weight-bold">{{ $message }}</small>
                                @else
                                    <small class="text-muted">03XXXXXXXXX (11 Digits)</small>
                                @enderror
                            </div>

                            <!-- Department -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-dark">Department</label>
                                <select name="department_id" class="form-select" required>
                                    <option value="" selected disabled>-- Select Department --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Semester -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-dark">Semester</label>
                                <select name="semester" class="form-select" required>
                                    <option value="" selected disabled>-- Select Semester --</option>
                                    @foreach($semesters as $sem)
                                        <option value="{{ $sem }}" {{ old('semester') == $sem ? 'selected' : '' }}>
                                            {{ $sem }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Session -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-dark">Session</label>
                                <input type="text" name="session" 
                                       class="form-control  " 
                                       value="{{ old('session') }}" placeholder="e.g. 2020-2024" required>
                            </div>

                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save me-2"></i> Save Teacher
                            </button>
                            <a href="{{ route('teachers.index') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-arrow-left me-2"></i> Back
                            </a>
                        </div>

                    </form>

                    <style>
                        .form-select { background-color: white !important; color: black !important; }
                        .::placeholder { color: #ffffff !important; opacity: 0.8; }
                    </style>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection