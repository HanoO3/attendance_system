@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Edit Subject</h2>
            <p class="text-light opacity-75 mb-0">Update subject details and manage teachers</p>
        </div>
    </div>

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

                    <form method="POST" action="{{ route('subjects.update', $subject->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label text-white">Subject Name</label>
                            <input type="text" name="name" class="form-control bg-transparent text-white border-light" 
                                   value="{{ old('name', $subject->name) }}" required>
                        </div>

                        <!-- UPDATED: Label changed to Course Code -->
                        <div class="mb-3">
                            <label class="form-label text-white">Course Code</label>
                            <input type="text" name="code" class="form-control bg-transparent text-white border-light" 
                                   value="{{ old('code', $subject->code) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Department</label>
                            <select name="department_id" class="form-select border-light" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ $subject->department_id == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Semester</label>
                            <select name="semester" class="form-select border-light" required>
                                @foreach(['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'] as $sem)
                                    <option value="{{ $sem }}" {{ $subject->semester == $sem ? 'selected' : '' }}>
                                        {{ $sem }} Semester
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update Subject
                        </button>
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary ms-2">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>

                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Manage Teachers -->
        <div class="col-lg-6 mb-4">
            <div class="card border-light shadow h-100">
                <div class="card-header py-3 border-light">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Manage Teachers</h5>
                </div>
                <div class="card-body">

                    <!-- Assign New Teacher Form -->
                    <form action="{{ route('subjects.assign') }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                        
                        <div class="row g-2">
                            <div class="col-md-5">
                                <select name="teacher_id" class="form-select border-light bg-transparent text-white" required>
                                    <option value="">Select Teacher</option>
                                    @foreach($teachers as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="session" class="form-control bg-transparent text-white border-light" placeholder="Session (e.g. 2022-2026)" required>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success w-100"><i class="fas fa-plus me-1"></i> Add</button>
                            </div>
                        </div>
                    </form>

                    <!-- Assigned Teachers List -->
                    <h6 class="text-light opacity-75 mb-3">Currently Assigned</h6>
                    @if($subject->teachers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0 align-middle">
                                <thead class="bg-transparent">
                                    <tr>
                                        <th class="border-0 text-light opacity-75 ps-0">Name</th>
                                        <th class="border-0 text-light opacity-75">Session</th>
                                        <th class="border-0 text-light opacity-75 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subject->teachers as $t)
                                    <tr>
                                        <td class="ps-0 text-white">{{ $t->name }}</td>
                                        <td class="text-white">{{ $t->pivot->session }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('subjects.removeTeacher', [$subject->id, $t->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Remove?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-light opacity-50 text-center">No teachers assigned.</p>
                    @endif

                </div>
            </div>
        </div>

    </div>

</div>

<style>
    .form-select { background-color: rgba(255,255,255,0.1) !important; color: #ffffff !important; }
    .form-select option { background-color: #ffffff !important; color: #000000 !important; }
    .form-control {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important; 
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }
    .form-control::placeholder { color: #ffffff !important; opacity: 0.6; }
</style>
@endsection