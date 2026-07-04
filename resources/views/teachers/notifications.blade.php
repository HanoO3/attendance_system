@extends('layouts.app')
@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Notifications</h1>
    <p style="font-size:.82rem;color:#6b7280;margin:0;">Important announcements and alerts</p>
</div>

@if($notifications->count() > 0)
<div class="card">
    <div class="card-header">
        <div class="hico"><i class="fas fa-bell"></i></div>
        All Notifications
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width:160px;">Date &amp; Time</th>
                        <th style="width:200px;">Title</th>
                        <th>Message</th>
                        <th class="text-center">View</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notifications as $notification)
                    <tr>
                        <td>
                            <div style="font-weight:600;color:#374151;">{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y') }}</div>
                            <div style="font-size:.75rem;color:#9ca3af;">{{ \Carbon\Carbon::parse($notification->created_at)->format('h:i A') }}</div>
                        </td>
                        <td style="font-weight:700;color:#111827;">{{ $notification->title }}</td>
                        <td style="color:#6b7280;">
                            {{ \Illuminate\Support\Str::limit($notification->message, 70) }}
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-info"
                                    data-bs-toggle="modal" data-bs-target="#viewNotifModal"
                                    data-title="{{ $notification->title }}"
                                    data-message="{{ $notification->message }}"
                                    data-date="{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y, h:i A') }}"
                                    title="View Full">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($notifications->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2"
         style="background:#f8fafc;border-top:1px solid #e5e7eb;padding:12px 18px;">
        <div style="font-size:.8rem;color:#6b7280;">
            Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of {{ $notifications->total() }} results
        </div>
        {{ $notifications->links() }}
    </div>
    @endif
</div>

@else
<div class="card">
    <div class="card-body text-center py-5" style="color:#9ca3af;">
        <i class="fas fa-bell-slash fa-3x mb-3 d-block opacity-50"></i>
        <h5>No Notifications</h5>
        <p>You have no notifications at the moment.</p>
    </div>
</div>
@endif

<!-- View Modal -->
<div class="modal fade" id="viewNotifModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight:700;font-size:.95rem;">
                    <i class="fas fa-bell me-2 text-info"></i>
                    <span id="n-title"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;color:#9ca3af;margin-bottom:5px;">Date &amp; Time</div>
                <div id="n-date" style="font-weight:600;color:#374151;margin-bottom:16px;"></div>
                <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;color:#9ca3af;margin-bottom:8px;">Message</div>
                <div id="n-message"
                     style="background:#f8fafc;border:1.5px solid #e5e7eb;border-radius:10px;padding:18px;color:#374151;font-size:.9rem;line-height:1.7;white-space:pre-wrap;min-height:80px;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('viewNotifModal').addEventListener('show.bs.modal', function(e) {
    var b = e.relatedTarget;
    document.getElementById('n-title').textContent   = b.getAttribute('data-title');
    document.getElementById('n-message').textContent = b.getAttribute('data-message');
    document.getElementById('n-date').textContent    = b.getAttribute('data-date');
});
</script>
<style>
ul.pagination{margin-bottom:0;}
.page-item .page-link{background:#fff;border:1.5px solid #e5e7eb;color:#374151;padding:.45rem .8rem;font-size:.82rem;border-radius:7px!important;margin:0 2px;transition:all .15s;font-family:'Outfit',sans-serif;font-weight:600;}
.page-item .page-link:hover{background:#f3f4f6;border-color:#6366f1;color:#6366f1;}
.page-item.active .page-link{background:#6366f1;border-color:#6366f1;color:#fff;}
.page-item.disabled .page-link{background:#f9fafb;color:#d1d5db;border-color:#e5e7eb;}
</style>
@endsection