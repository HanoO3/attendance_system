@extends('layouts.app')

@section('content')
<div>
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Teacher Management</h2>
            <p class="text-muted mb-0">Filter and manage records</p>
        </div>
        <a href="{{ route('teachers.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i> Add New Teacher
        </a>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('teachers.index') }}" class="row g-3">
                
                <!-- Department Filter -->
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label text-muted">Department</label>
                    <select id="filterDept" name="department_id" class="form-select custom-select-dark">
                        <option value="all">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Semester Filter -->
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label text-muted">Semester</label>
                    <select id="filterSem" name="semester" class="form-select custom-select-dark">
                        <option value="all">All Semesters</option>
                        @foreach(['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'] as $sem)
                            <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>
                                {{ $sem }} Semester
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Session Filter -->
                <div class="col-md-4">
                    <label class="form-label text-muted">Session</label>
                    <input type="text" name="session" id="filterSession" value="{{ request('session') }}" 
                           class="form-control  " 
                           placeholder="e.g. 2020-2024">
                </div>

            </form> <!-- Fixed Missing Form Close Tag -->
        </div>
    </div>

    <!-- Teachers Table Card -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success m-3 mb-0">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="teacherTable">
                    <thead class="bg-transparent">
                        <tr>
                            <th class="border-0 text-muted ps-4">Sr#</th>
                            <th class="border-0 text-muted">Name</th>
                            <th class="border-0 text-muted">Email</th>
                            <th class="border-0 text-muted">Department</th>
                            <th class="border-0 text-muted">Semester</th>
                            <th class="border-0 text-muted">Session</th>
                            <th class="border-0 text-muted">Contact</th>
                            <th class="border-0 text-muted text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                        <tr class="teacher-row"
                            data-dept="{{ $teacher->department_id }}" 
                            data-sem="{{ $teacher->semester }}" 
                            data-sess="{{ $teacher->session }}">
                            
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->user->email ?? 'N/A' }}</td>
                            <td>{{ $teacher->department->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-info bg-opacity-25 text-info">{{ $teacher->semester }}</span></td>
                            <td>{{ $teacher->session ?? 'N/A' }}</td>
                            <td>{{ $teacher->contact }}</td>
                            <td class="text-center">
                                <!-- FIXED: Flex Container for One Line Alignment -->
                                <div class="d-flex justify-content-center align-items-center gap-1">
                                    
                                    <!-- VIEW BUTTON -->
                                    <a href="{{ route('teachers.show', $teacher->id) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- EDIT BUTTON -->
                                    <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    
                                    <!-- DELETE BUTTON -->
                                    <form method="POST" action="{{ route('teachers.destroy', $teacher->id) }}" onsubmit="return confirm('Are you sure?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                <h5>No Teachers Found</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- No Results Message -->
            <div id="noResults" class="text-center py-5 d-none">
                <i class="fas fa-search fa-3x mb-3 opacity-50"></i>
                <h5>No Matching Records</h5>
                <p class="text-muted">Try adjusting your filters.</p>
            </div>

        </div>
    </div>

</div>

<style>
    /* Custom Arrow Icon */
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%28255, 255, 255, 0.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    }

    /* Table Row Hover */
    #teacherTable tbody tr:hover {
        background-color: rgba(255,255,255,0.05) !important;
    }

    /* Dropdown Styling */
    .custom-select-dark option {
        background-color: #ffffff !important; 
        color: #000000 !important;            
        padding: 10px;
        font-weight: 500;
    }

    .custom-select-dark option:checked {
        background-color: #f8f9fa !important;
        color: #000000 !important;
    }

    /* Session input placeholder white */
    .::placeholder { color: #ffffff !important; opacity: 0.75; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const deptSelect = document.getElementById('filterDept');
        const semSelect = document.getElementById('filterSem');
        const sessInput = document.getElementById('filterSession');
        const rows = document.querySelectorAll('.teacher-row');
        const noResultsDiv = document.getElementById('noResults');
        const emptyRow = document.getElementById('emptyRow');

        function filterTable() {
            const deptVal = deptSelect.value.toString();
            const semVal = semSelect.value.toString();
            const sessVal = sessInput.value.trim().toLowerCase(); 

            let visibleCount = 0;

            rows.forEach(row => {
                const rowDept = row.getAttribute('data-dept').toString();
                const rowSem = row.getAttribute('data-sem').toString();
                const rowSess = row.getAttribute('data-sess').toString().toLowerCase();

                const matchDept = (deptVal === 'all' || deptVal === rowDept);
                const matchSem = (semVal === 'all' || semVal === rowSem);
                const matchSess = (sessVal === '' || rowSess.includes(sessVal)); 

                if (matchDept && matchSem && matchSess) {
                    row.style.display = ''; 
                    visibleCount++;
                } else {
                    row.style.display = 'none'; 
                }
            });

            if (visibleCount === 0 && emptyRow === null) {
                noResultsDiv.classList.remove('d-none');
            } else {
                noResultsDiv.classList.add('d-none');
            }
        }

        deptSelect.addEventListener('change', filterTable);
        semSelect.addEventListener('change', filterTable);
        sessInput.addEventListener('input', filterTable); 

      
        filterTable();
    });
</script>
@endsection