@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Student Management</h2>
            <p class="text-light opacity-75 mb-0">Filter and manage records</p>
        </div>
        <a href="{{ route('students.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i> Add New Student
        </a>
    </div>

    <!-- Filter Card -->
    <div class="card border-light shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('students.index') }}" class="row g-3">
                
                <!-- Department Filter -->
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label text-light opacity-75">Department</label>
                    <select id="filterDept" name="department_id" class="form-select bg-transparent text-white border-light custom-select-dark">
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
                    <label class="form-label text-light opacity-75">Semester</label>
                    <select id="filterSem" name="semester" class="form-select bg-transparent text-white border-light custom-select-dark">
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
                    <label class="form-label text-light opacity-75">Session</label>
                    <input type="text" name="session" id="filterSession" value="{{ request('session') }}" 
                           class="form-control bg-transparent text-white border-light placeholder-white" 
                           placeholder="e.g. 2020-2024">
                </div>

            </div>
        </div>
    </div>

    <!-- Students Table Card -->
    <div class="card border-light shadow">
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success m-3 mb-0">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="studentTable">
                    <thead class="bg-transparent">
                        <tr>
                            <th class="border-0 text-light opacity-75">Sr#</th>
                            <th class="border-0 text-light opacity-75">Roll No</th>
                            <th class="border-0 text-light opacity-75">Name</th>
                            <th class="border-0 text-light opacity-75">Father Name</th>
                            <th class="border-0 text-light opacity-75">Department</th>
                            <th class="border-0 text-light opacity-75">Semester</th>
                            <th class="border-0 text-light opacity-75">Session</th>
                            <th class="border-0 text-light opacity-75">Contact</th>
                            <th class="border-0 text-light opacity-75 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr class="student-row"
                            data-dept="{{ $student->department_id }}" 
                            data-sem="{{ $student->semester }}" 
                            data-sess="{{ $student->session }}">
                            
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-primary bg-opacity-25 text-primary">{{ $student->roll_number }}</span></td>
                            <td>{{ $student->student_name }}</td>
                            <td>{{ $student->father_name }}</td>
                            <td>{{ $student->department->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-info bg-opacity-25 text-info">{{ $student->semester }}</span></td>
                            <td>{{ $student->session ?? 'N/A' }}</td>
                            <td>{{ $student->contact_number }}</td>
                            <td class="text-center">
                                <!-- VIEW BUTTON (Cyan Outline - Same as Teacher) -->
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-outline-info me-1" title="View Profile">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- EDIT BUTTON (Yellow Outline - Same as Teacher) -->
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit Student">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <!-- DELETE BUTTON (Red Outline - Same as Teacher) -->
                                <form method="POST" action="{{ route('students.destroy', $student->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Student">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                <h5>No Students Found</h5>
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
                <p class="text-light opacity-50">Try adjusting your filters.</p>
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
    #studentTable tbody tr:hover {
        background-color: rgba(255,255,255,0.05) !important;
    }

    /* Dropdown Styling (White + Black Text) */
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

    /* Session placeholder white fix */
    .placeholder-white::placeholder { color: #ffffff !important; opacity: 0.75; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const deptSelect = document.getElementById('filterDept');
        const semSelect = document.getElementById('filterSem');
        const sessInput = document.getElementById('filterSession'); 
        const rows = document.querySelectorAll('.student-row');
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
                const rowSess = row.getAttribute('data-sess')?.toString().toLowerCase() || '';

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