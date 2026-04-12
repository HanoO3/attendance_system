@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Notification History</h2>
            <p class="text-light opacity-75 mb-0">Sent notifications record</p>
        </div>
        <a href="{{ route('notifications.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Send New
        </a>
    </div>

    @if($logs->count() > 0)
    <div class="card border-light shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Title</th>
                            <th>Message</th> <!-- Added Message Column -->
                            <th>Sent To</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        @php
                            $data = json_decode($log->properties, true);
                        @endphp
                        <tr>
                            <td>{{ $log->created_at->format('d M Y - h:i A') }}</td>
                            <td><strong>{{ $data['title'] ?? $log->description }}</strong></td>
                            <!-- Added Message Data -->
                            <td>{{ \Illuminate\Support\Str::limit($data['message'] ?? '-', 50) }}</td>
                            <td>
                                @if(($data['sent_to'] ?? '') == 'all')
                                    <span class="badge bg-success">All</span>
                                @elseif(($data['sent_to'] ?? '') == 'all_students')
                                    <span class="badge bg-info">Students</span>
                                @elseif(($data['sent_to'] ?? '') == 'all_teachers')
                                    <span class="badge bg-warning text-dark">Teachers</span>
                                @else
                                    <span class="badge bg-secondary">{{ $data['sent_to'] ?? 'N/A' }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <!-- View Button REMOVED -->
                                
                                <!-- Delete Button -->
                                <form action="{{ route('notifications.history.destroy', $log->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Record">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        @if($logs->hasPages())
        <div class="card-footer d-flex justify-content-center">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="card border-light shadow">
        <div class="card-body text-center py-5">
            <i class="fas fa-bell-slash fa-3x mb-3 opacity-50"></i>
            <h5>No History Found</h5>
            <p class="text-light opacity-50">No notifications sent yet.</p>
        </div>
    </div>
    @endif

</div>
@endsection