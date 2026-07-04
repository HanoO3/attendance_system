@extends('layouts.app')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Edit Subject</h1>
        <p style="font-size:.82rem;color:#6b7280;margin:0;">Update subject details and manage teachers</p>
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
<div class="row g-4">
    <!-- Subject Details -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <div class="hico"><i class="fas fa-book"></i></div>
                Subject Details
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('subjects.update', $subject->id) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Subject Name</label>
                        <input type="text" name="name" class="form-control"
                               style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                               value="{{ old('name', $subject->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="code" class="form-control"
                               style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                               value="{{ old('code', $subject->code) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select"
                                style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ $subject->department_id == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select"
                                style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
                            @foreach(['1st','2nd','3rd','4th','5th','6th','7th','8th'] as $sem)
                                <option value="{{ $sem }}" {{ $subject->semester == $sem ? 'selected' : '' }}>
                                    {{ $sem }} Semester
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update Subject
                        </button>
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Manage Teachers -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <div class="hico"><i class="fas fa-user-plus"></i></div>
                Manage Teachers
            </div>
            <div class="card-body p-4">
                <!-- Assign New Teacher -->
                <form action="{{ route('subjects.assign') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <select name="teacher_id" class="form-select"
                                    style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
                                <option value="" disabled selected>Select Teacher</option>
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="session" class="form-control"
                                   style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                                   placeholder="Session e.g. 2022-2026" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-plus me-1"></i> Add
                            </button>
                        </div>
                    </div>
                </form>
                <!-- Assigned List -->
                <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:10px;">Currently Assigned</div>
                @if($subject->teachers->count() > 0)
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Session</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subject->teachers as $t)
                            <tr>
                                <td style="font-weight:600;color:#111827;">{{ $t->name }}</td>
                                <td style="color:#6b7280;">{{ $t->pivot->session }}</td>
                                <td class="text-center">
                                    <form action="{{ route('subjects.removeTeacher', [$subject->id, $t->id]) }}" method="POST"
                                          onsubmit="return confirm('Remove this teacher?')">
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
                <div class="text-center py-3" style="color:#9ca3af;">
                    <i class="fas fa-user-slash mb-2 d-block"></i>
                    No teachers assigned yet.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection