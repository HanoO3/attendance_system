@extends('layouts.app')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Add New Subject</h1>
        <p style="font-size:.82rem;color:#6b7280;margin:0;">Create subject and assign teacher</p>
    </div>
    <a href="{{ route('subjects.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-3">
    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form method="POST" action="{{ route('subjects.store') }}">
    @csrf
    <div class="row g-4">
        <!-- Subject Details -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <div class="hico"><i class="fas fa-book"></i></div>
                    Subject Details
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Subject Name</label>
                        <input type="text" name="name"
                               class="form-control"
                               style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                               value="{{ old('name') }}" placeholder="e.g. Data Structures" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="code"
                               class="form-control"
                               style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                               value="{{ old('code') }}" placeholder="e.g. CS-201" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select" style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
                            <option value="" disabled selected>-- Select Department --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select" style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
                            <option value="" disabled selected>-- Select Semester --</option>
                            @foreach(['1st','2nd','3rd','4th','5th','6th','7th','8th'] as $sem)
                                <option value="{{ $sem }}" {{ old('semester') == $sem ? 'selected' : '' }}>{{ $sem }} Semester</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assign Teacher -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <div class="hico"><i class="fas fa-user-plus"></i></div>
                    Assign Teacher <span style="font-size:.72rem;color:#9ca3af;font-weight:400;">(Optional)</span>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Select Teacher</label>
                        <select name="teacher_id" class="form-select" style="background:#fff;border:1.5px solid #d1d5db;color:#111827;">
                            <option value="">-- None --</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Session</label>
                        <input type="text" name="session_assign"
                               class="form-control"
                               style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                               value="{{ old('session_assign') }}" placeholder="e.g. 2022-2026">
                        <div class="form-text">Required if selecting a teacher</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-5">
            <i class="fas fa-save me-2"></i> Save Subject
        </button>
        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>
</form>
@endsection