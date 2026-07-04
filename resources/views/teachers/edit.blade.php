@extends('layouts.app')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Edit Teacher</h1>
        <p style="font-size:.82rem;color:#6b7280;margin:0;">Update teacher details</p>
    </div>
    <a href="{{ route('teachers.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <div class="hico"><i class="fas fa-user-edit"></i></div>
                Teacher Information
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif
                <form method="POST" action="{{ route('teachers.update', $teacher->id) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name"
                                   class="form-control"
                                   style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                                   value="{{ old('name', $teacher->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <small class="text-muted">(Cannot be changed)</small></label>
                            <input type="email"
                                   class="form-control"
                                   style="background:#f8fafc;border:1.5px solid #d1d5db;color:#6b7280;cursor:not-allowed;"
                                   value="{{ $teacher->user->email ?? 'N/A' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact"
                                   class="form-control"
                                   style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                                   value="{{ old('contact', $teacher->contact) }}"
                                   maxlength="11"
                                   placeholder="03XXXXXXXXX"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <div class="form-text">Format: 03XXXXXXXXX (11 digits)</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-select"
                                    style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ $teacher->department_id == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select"
                                    style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
                                @foreach($semesters as $sem)
                                    <option value="{{ $sem }}" {{ $teacher->semester == $sem ? 'selected' : '' }}>
                                        {{ $sem }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Session</label>
                            <input type="text" name="session"
                                   class="form-control"
                                   style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                                   value="{{ old('session', $teacher->session) }}"
                                   placeholder="e.g. 2022-2026" required>
                        </div>
                        <div class="col-12 mt-2">
                            <hr style="border-color:#e5e7eb;">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i> Update Teacher
                                </button>
                                <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
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