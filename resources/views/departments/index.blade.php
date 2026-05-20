@extends('layouts.app')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Department Management</h1>
        <p style="font-size:.82rem;color:#6b7280;margin:0;">Manage all departments</p>
    </div>
    <a href="{{ route('departments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add New Department
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div class="hico"><i class="fas fa-building"></i></div>
        All Departments
    </div>
    <div class="card-body p-0">
        @if($departments->count() > 0)
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th class="text-center">Course Code</th>
                        <th class="text-center">Students</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $dept)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="font-weight:600;">{{ $dept->name }}</td>
                        <td class="text-center">
                            <span class="badge bg-info bg-opacity-10 text-info fw-bold">
                                {{ \App\Models\Department::generateCourseCode($dept->name) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">
                                {{ $dept->students_count }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                <a href="{{ route('departments.show', $dept->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('departments.edit', $dept->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('departments.destroy', $dept->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5" style="color:#9ca3af;">
            <i class="fas fa-building fa-3x mb-3 d-block opacity-50"></i>
            <h5>No Departments Found</h5>
            <p>Click "Add New Department" to get started.</p>
        </div>
        @endif
    </div>
</div>

@endsection