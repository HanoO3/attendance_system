@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Department Management</h2>
            <p class="text-light opacity-75 mb-0">Manage all departments</p>
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

    <!-- Departments List -->
    <div class="card border-light shadow">
        <div class="card-body p-0">
            @if($departments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-transparent">
                        <tr>
                            <th class="border-0 text-light opacity-75 ps-4" style="width: 80px;">Sr#</th>
                            <th class="border-0 text-light opacity-75">Name</th>
                            <th class="border-0 text-light opacity-75 text-center">Course Code</th>
                            <th class="border-0 text-light opacity-75 text-center">Students</th>
                            <th class="border-0 text-light opacity-75 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departments as $dept)
                        <tr>
                            <td class="ps-4 text-white">{{ $loop->iteration }}</td>
                            <td class="text-white">{{ $dept->name }}</td>
                            <td class="text-center">
                                <span class="badge bg-info bg-opacity-25 text-info">
                                    {{ \App\Models\Department::generateCourseCode($dept->name) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">
                                    {{ $dept->students_count }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-1">
                                    
                                    <!-- View Button (Cyan) -->
                                    <a href="{{ route('departments.show', $dept->id) }}" class="btn btn-sm btn-outline-info" title="View Profile">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- UPDATED: Edit Button (Yellow) -->
                                    <a href="{{ route('departments.edit', $dept->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit Department">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <!-- Delete Button (Red) -->
                                    <form action="{{ route('departments.destroy', $dept->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Department">
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
            <div class="text-center py-5">
                <i class="fas fa-users-slash fa-3x mb-3 opacity-50"></i>
                <h5>No Departments Found</h5>
                <p class="text-light opacity-50">Click "Add New Department" to get started.</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection