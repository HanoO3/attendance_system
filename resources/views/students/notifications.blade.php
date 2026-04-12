@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Notifications</h2>
            <p class="text-light opacity-75 mb-0">Important announcements and alerts</p>
        </div>
    </div>

    <!-- Notifications List -->
    @if($notifications->count() > 0)
    <div class="card border-light shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-transparent">
                        <tr>
                            <th class="border-0 text-light opacity-75" style="width: 180px;">Date & Time</th>
                            <th class="border-0 text-light opacity-75" style="width: 200px;">Title</th>
                            <th class="border-0 text-light opacity-75">Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $notification)
                        <tr>
                            <td>
                                <small class="text-light opacity-50">{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y') }}</small>
                                <div class="text-white">{{ \Carbon\Carbon::parse($notification->created_at)->format('h:i A') }}</div>
                            </td>
                            <td>
                                <!-- Simple Bold White Text -->
                                <span class="fw-bold text-white">{{ $notification->title }}</span>
                            </td>
                            <td class="text-white">
                                {{ $notification->message }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination Footer -->
        <div class="card-footer d-flex justify-content-center align-items-center flex-wrap gap-2" style="background: rgba(0,0,0,0.2); border-top: 1px solid rgba(255,255,255,0.1);">
            <!-- Results Count -->
            <div class="text-light opacity-50 small me-auto">
                Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of {{ $notifications->total() }} results
            </div>

            <!-- Pagination Links -->
            {{ $notifications->links() }}
        </div>
    </div>
    @else
    <div class="card border-light shadow">
        <div class="card-body text-center py-5">
            <i class="fas fa-bell-slash fa-3x mb-3 opacity-50"></i>
            <h5>No Notifications</h5>
            <p class="text-light opacity-50">You have no notifications at the moment.</p>
        </div>
    </div>
    @endif

</div>

<style>
    /* PAGINATION THEME MATCHING */
    ul.pagination {
        margin-bottom: 0;
    }

    .page-item .page-link {
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 0.5rem 0.85rem;
        font-size: 0.9rem;
        margin: 0 2px;
        border-radius: 0.4rem;
        transition: all 0.2s;
    }

    .page-item .page-link:hover {
        background-color: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        color: #ffffff;
        transform: translateY(-1px);
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, rgba(118, 75, 162, 0.8), rgba(118, 75, 162, 0.6));
        border-color: rgba(118, 75, 162, 0.8);
        color: #ffffff;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(118, 75, 162, 0.4);
    }

    .page-item.disabled .page-link {
        background-color: rgba(255, 255, 255, 0.02);
        border-color: rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.3);
        cursor: not-allowed;
    }
</style>
@endsection