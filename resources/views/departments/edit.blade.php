@extends('layouts.app')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Edit Department</h1>
        <p style="font-size:.82rem;color:#6b7280;margin:0;">Update department name</p>
    </div>
    <a href="{{ route('departments.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="hico"><i class="fas fa-building"></i></div>
                Department Details
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif
                <form method="POST" action="{{ route('departments.update', $department->id) }}">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="form-label">Department Name</label>
                        <input type="text" name="name"
                               class="form-control"
                               style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                               value="{{ old('name', $department->name) }}"
                               placeholder="e.g. BS Computer Science" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i> Update Department
                        </button>
                        <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection