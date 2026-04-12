@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Edit Teacher</h2>
            <p class="text-light opacity-75 mb-0">Update teacher details</p>
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

                    <form method="POST" action="{{ route('teachers.update', $teacher->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Full Name</label>
                                <input type="text" name="name" value="{{ $teacher->name }}" 
                                       class="form-control bg-transparent text-white border-light placeholder-white" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Email Address</label>
                                <input type="email" value="{{ $teacher->user->email ?? 'N/A' }}" 
                                       class="form-control bg-transparent text-white border-light" disabled>
                                <small class="text-light opacity-50">Email cannot be changed.</small>
                            </div>

                            <!-- Contact (Required removed for Server Validation) -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Contact Number</label>
                                <input type="text" name="contact" 
                                       value="{{ $teacher->contact }}" 
                                       class="form-control bg-transparent text-white border-light placeholder-white" 
                                       maxlength="11" 
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                
                                @error('contact')
                                    <small class="text-danger">{{ $message }}</small>
                                @else
                                    <small class="text-light opacity-50">03XXXXXXXXX (11 Digits)</small>
                                @enderror
                            </div>

                            <!-- Department -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Department</label>
                                <select name="department_id" class="form-select border-light" required>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ $teacher->department_id == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Semester -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Semester</label>
                                <select name="semester" class="form-select border-light" required>
                                    @foreach($semesters as $sem)
                                        <option value="{{ $sem }}" {{ $teacher->semester == $sem ? 'selected' : '' }}>
                                            {{ $sem }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Session -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Session</label>
                                <input type="text" name="session" 
                                       value="{{ $teacher->session }}" 
                                       class="form-control bg-transparent text-white border-light placeholder-white" 
                                       placeholder="e.g. 2020-2024" required>
                            </div>

                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save me-2"></i> Update Teacher
                            </button>
                            <a href="{{ route('teachers.index') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-arrow-left me-2"></i> Back
                            </a>
                        </div>

                    </form>

                    <style>
                        .form-select { background-color: white !important; color: black !important; }
                        .form-select option { background-color: white !important; color: black !important; }
                    </style>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection