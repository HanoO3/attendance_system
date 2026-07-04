@extends('layouts.app')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Activity Logs</h1>
    <p style="font-size:.82rem;color:#6b7280;margin:0;">System activity and history records</p>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-header">
        <div class="hico"><i class="fas fa-filter"></i></div>
        Search & Filter
    </div>
    <div class="card-body p-3" style="background:#f8fafc;border-radius:0 0 12px 12px;">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="row g-3">

            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control"
                       style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                       placeholder="Name or description...">
            </div>

            <div class="col-md-3">
                <label class="form-label">Log Type</label>
                <select name="type" class="form-select" style="background:#fff;border:1.5px solid #d1d5db;color:#111827;">
                    <option value="">All Types</option>
                    <option value="Student Created"   {{ request('type') == 'Student Created'   ? 'selected' : '' }}>Student Created</option>
                    <option value="Student Updated"   {{ request('type') == 'Student Updated'   ? 'selected' : '' }}>Student Updated</option>
                    <option value="Student Deleted"   {{ request('type') == 'Student Deleted'   ? 'selected' : '' }}>Student Deleted</option>
                    <option value="Teacher Created"   {{ request('type') == 'Teacher Created'   ? 'selected' : '' }}>Teacher Created</option>
                    <option value="Teacher Updated"   {{ request('type') == 'Teacher Updated'   ? 'selected' : '' }}>Teacher Updated</option>
                    <option value="Teacher Deleted"   {{ request('type') == 'Teacher Deleted'   ? 'selected' : '' }}>Teacher Deleted</option>
                    <option value="Notification"      {{ request('type') == 'Notification'      ? 'selected' : '' }}>Notification</option>
                    <option value="Attendance Marked" {{ request('type') == 'Attendance Marked' ? 'selected' : '' }}>Attendance Marked</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="form-control"
                       style="background:#fff;border:1.5px solid #d1d5db;color:#111827;">
            </div>

            <div class="col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="form-control"
                       style="background:#fff;border:1.5px solid #d1d5db;color:#111827;">
            </div>

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
<div class="card">
    <div class="card-header">
        <div class="hico"><i class="fas fa-history"></i></div>
        Activity Records
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Date &amp; Time</th>
                        <th>User</th>
                        <th>Activity</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td style="color:#6b7280;font-size:.82rem;white-space:nowrap;">
                            {{ $log->created_at->format('d M Y - h:i A') }}
                        </td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">
                                {{ $log->causer->name ?? 'System' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge fw-semibold me-2
                                @if(str_contains($log->log_name, 'Created')) bg-success
                                @elseif(str_contains($log->log_name, 'Updated')) bg-warning text-dark
                                @elseif(str_contains($log->log_name, 'Deleted')) bg-danger
                                @elseif(str_contains($log->log_name, 'Attendance')) bg-info
                                @else bg-secondary @endif">
                                {{ $log->log_name }}
                            </span>
                            <span style="color:#374151;font-size:.85rem;">{{ $log->description }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('activity-logs.show', $log->id) }}"
                                   class="btn btn-sm btn-outline-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('activity-logs.destroy', $log->id) }}" method="POST"
                                      style="display:inline;" onsubmit="return confirm('Delete this log?')">
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
    </div>

    <!-- Pagination -->
    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2"
         style="background:#f8fafc;border-top:1px solid #e5e7eb;padding:12px 18px;">
        <div style="font-size:.8rem;color:#6b7280;">
            Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} results
        </div>
        <div>{{ $logs->appends(request()->query())->links() }}</div>
    </div>
</div>

@else
<div class="card">
    <div class="card-body text-center py-5" style="color:#9ca3af;">
        <i class="fas fa-history fa-3x mb-3 d-block opacity-50"></i>
        <h5>No Activity Logs Found</h5>
        <p>Try adjusting your search filters.</p>
    </div>
</div>
@endif

@endsection

@section('scripts')
<style>
/* Clean pagination */
ul.pagination { margin-bottom: 0; }
.page-item .page-link {
    background: #fff;
    border: 1.5px solid #e5e7eb;
    color: #374151;
    padding: .45rem .8rem;
    font-size: .82rem;
    border-radius: 7px !important;
    margin: 0 2px;
    transition: all .15s;
    font-family: 'Outfit', sans-serif;
    font-weight: 600;
}
.page-item .page-link:hover { background: #f3f4f6; border-color: #6366f1; color: #6366f1; }
.page-item.active .page-link { background: #6366f1; border-color: #6366f1; color: #fff; }
.page-item.disabled .page-link { background: #f9fafb; color: #d1d5db; border-color: #e5e7eb; }
</style>
@endsection