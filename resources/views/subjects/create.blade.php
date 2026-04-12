@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Add New Subject</h2>
            <p class="text-light opacity-75 mb-0">Create subject and assign teacher</p>
        </div>
    </div>

    <!-- Single Form Wrapping Both Columns -->
    <form method="POST" action="{{ route('subjects.store') }}">
        @csrf
        <div class="row">
            
            <!-- Left Column: Subject Details -->
            <div class="col-lg-6 mb-4">
                <div class="card border-light shadow h-100">
                    <div class="card-header py-3 border-light">
                        <h5 class="mb-0"><i class="fas fa-book me-2"></i>Subject Details</h5>
                    </div>
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

                        <div class="mb-3">
                            <label class="form-label text-white">Subject Name</label>
                            <input type="text" name="name" class="form-control bg-transparent text-white border-light" 
                                   value="{{ old('name') }}" placeholder="e.g. Data Structures" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Course Code</label>
                            <input type="text" name="code" class="form-control bg-transparent text-white border-light" 
                                   value="{{ old('code') }}" placeholder="e.g. CS-201" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Department</label>
                            <select name="department_id" class="form-select border-light" required>
                                <option value="" selected disabled>-- Select Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Semester</label>
                            <select name="semester" class="form-select border-light" required>
                                <option value="" selected disabled>-- Select Semester --</option>
                                @foreach(['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'] as $sem)
                                    <option value="{{ $sem }}" {{ old('semester') == $sem ? 'selected' : '' }}>
                                        {{ $sem }} Semester
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Column: Manage Teachers -->
            <div class="col-lg-6 mb-4">
                <div class="card border-light shadow h-100">
                    <div class="card-header py-3 border-light">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Assign Teacher (Optional)</h5>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Select Teacher</label>
                            <select name="teacher_id" class="form-select border-light bg-transparent text-white">
                                <option value="">-- None --</option>
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Session</label>
                            <input type="text" name="session_assign" class="form-control bg-transparent border-light" 
                                   value="{{ old('session_assign') }}" placeholder="e.g. 2022-2026">
                            <small class="text-light opacity-50">Required if selecting a teacher</small>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- Action Buttons at the end -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-5">
                <i class="fas fa-save me-2"></i> Save Subject
            </button>
            <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>

    </form>

</div>

<style>
    /* Input Text Color Fix */
    .form-control {
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: #ffffff !important; /* Force White Text */
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }

    /* Placeholder Color Fix */
    .form-control::placeholder {
        color: #ffffff !important;
        opacity: 0.6; /* Slightly faded but white */
    }

    /* Select Dropdown Fix */
    .form-select {
        background-color: rgba(255,255,255,0.1) !important; 
        color: #ffffff !important; 
        border: 1px solid rgba(255,255,255,0.2) !important;
    }
    .form-select option { 
        background-color: #ffffff !important; 
        color: #000000 !important; 
    }
</style>
@endsection