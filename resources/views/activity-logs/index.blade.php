@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Activity Logs</h2>
            <p class="text-light opacity-75 mb-0">System activity and history records</p>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="card border-light shadow mb-4">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('activity-logs.index') }}" class="row g-3">
                
                <!-- Search Keyword -->
                <div class="col-md-3">
                    <label class="text-light opacity-75">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="form-control bg-transparent text-white border-light placeholder-white" 
                           placeholder="Name or Description...">
                </div>

                <!-- Log Type -->
                <div class="col-md-3">
                    <label class="text-light opacity-75">Log Type</label>
                    <select name="type" class="form-select bg-transparent text-white border-light custom-select-dark">
                        <option value="">All Types</option>
                        <option value="Student Created" {{ request('type') == 'Student Created' ? 'selected' : '' }}>Student Created</option>
                        <option value="Student Updated" {{ request('type') == 'Student Updated' ? 'selected' : '' }}>Student Updated</option>
                        <option value="Student Deleted" {{ request('type') == 'Student Deleted' ? 'selected' : '' }}>Student Deleted</option>
                        <option value="Teacher Created" {{ request('type') == 'Teacher Created' ? 'selected' : '' }}>Teacher Created</option>
                        <option value="Teacher Updated" {{ request('type') == 'Teacher Updated' ? 'selected' : '' }}>Teacher Updated</option>
                        <option value="Teacher Deleted" {{ request('type') == 'Teacher Deleted' ? 'selected' : '' }}>Teacher Deleted</option>
                        <option value="Notification" {{ request('type') == 'Notification' ? 'selected' : '' }}>Notification</option>
                        <option value="Attendance Marked" {{ request('type') == 'Attendance Marked' ? 'selected' : '' }}>Attendance Marked</option>
                    </select>
                </div>

                <!-- Date From -->
                <div class="col-md-2">
                    <label class="text-light opacity-75">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="form-control bg-transparent text-white border-light">
                </div>

                <!-- Date To -->
                <div class="col-md-2">
                    <label class="text-light opacity-75">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="form-control bg-transparent text-white border-light">
                </div>

                <!-- Buttons -->
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary" title="Reset">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    @if($logs->count() > 0)
    <div class="card border-light shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-transparent">
                        <tr>
                            <th class="border-0 text-light opacity-75">Date & Time</th>
                            <th class="border-0 text-light opacity-75">User</th>
                            <th class="border-0 text-light opacity-75">Activity</th>
                            <th class="border-0 text-light opacity-75 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d M Y - h:i A') }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $log->causer->name ?? 'System' }}</span>
                            </td>
                            <td>
                                <span class="badge 
                                    @if(str_contains($log->log_name, 'Created')) bg-success
                                    @elseif(str_contains($log->log_name, 'Updated')) bg-warning text-dark
                                    @elseif(str_contains($log->log_name, 'Deleted')) bg-danger
                                    @elseif(str_contains($log->log_name, 'Attendance')) bg-info
                                    @else bg-secondary @endif">
                                    {{ $log->log_name }}
                                </span>
                                <span class="text-white ms-1">{{ $log->description }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-1">
                                    <a href="{{ route('activity-logs.show', $log->id) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <form action="{{ route('activity-logs.destroy', $log->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Log">
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
        </div>
        
        <!-- Pagination Footer -->
        <div class="card-footer d-flex justify-content-center align-items-center flex-wrap gap-2" style="background: rgba(0,0,0,0.2); border-top: 1px solid rgba(255,255,255,0.1);">
            <!-- Results Count Text -->
            <div class="text-light opacity-50 small me-auto">
                Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} results
            </div>

            <!-- Pagination Links -->
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
    @else
    <div class="card border-light shadow">
        <div class="card-body text-center py-5">
            <i class="fas fa-history fa-3x mb-3 opacity-50"></i>
            <h5>No Activity Logs Found</h5>
            <p class="text-light opacity-50">Try adjusting your search filters.</p>
        </div>
    </div>
    @endif

</div>

<style>
    /* Custom Arrow Icon */
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%28255, 255, 255, 0.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    }

    /* Dropdown Styling (White BG + Black Text) */
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

    /* Placeholder White */
    .placeholder-white::placeholder {
        color: #ffffff !important;
        opacity: 1;
    }
    .placeholder-white::-webkit-input-placeholder { color: #ffffff !important; opacity: 1; }
    .placeholder-white::-moz-placeholder          { color: #ffffff !important; opacity: 1; }
    .placeholder-white:-ms-input-placeholder      { color: #ffffff !important; opacity: 1; }
    .placeholder-white::-ms-input-placeholder     { color: #ffffff !important; opacity: 1; }

    /* =============================================== */
    /* PAGINATION THEME MATCHING                      */
    /* =============================================== */

    /* Pagination Wrapper */
    ul.pagination {
        margin-bottom: 0;
    }

    /* Pagination Items */
    .page-item .page-link {
        background-color: rgba(255, 255, 255, 0.05); /* Dark Semi-transparent */
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 0.5rem 0.85rem;
        font-size: 0.9rem;
        margin: 0 2px;
        border-radius: 0.4rem;
        transition: all 0.2s;
    }

    /* Hover State */
    .page-item .page-link:hover {
        background-color: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        color: #ffffff;
        transform: translateY(-1px);
    }

    /* Active Page (Purple Gradient) */
    .page-item.active .page-link {
        background: linear-gradient(135deg, rgba(118, 75, 162, 0.8), rgba(118, 75, 162, 0.6));
        border-color: rgba(118, 75, 162, 0.8);
        color: #ffffff;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(118, 75, 162, 0.4);
    }

    /* Disabled State */
    .page-item.disabled .page-link {
        background-color: rgba(255, 255, 255, 0.02);
        border-color: rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.3);
        cursor: not-allowed;
    }

    /* First/Last buttons usually have icons, ensure alignment */
    .page-item:first-child .page-link,
    .page-item:last-child .page-link {
        min-width: 40px;
        text-align: center;
    }

</style>
@endsection