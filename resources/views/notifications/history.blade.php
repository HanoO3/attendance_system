@extends('layouts.app')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
    <div>
        <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Notification History</h1>
        <p style="font-size:.82rem;color:#6b7280;margin:0;">All sent notifications record</p>
    </div>
    <a href="{{ route('notifications.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Send New
    </a>
</div>

@if($logs->count() > 0)
<div class="card">
    <div class="card-header">
        <div class="hico"><i class="fas fa-bell"></i></div>
        Sent Notifications
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date &amp; Time</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Sent To</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    @php $data = json_decode($log->properties, true); @endphp
                    <tr>
                        <td style="color:#9ca3af;">{{ $loop->iteration }}</td>
                        <td style="color:#6b7280;font-size:.82rem;white-space:nowrap;">
                            {{ $log->created_at->format('d M Y - h:i A') }}
                        </td>
                        <td style="font-weight:700;color:#111827;">{{ $data['title'] ?? $log->description }}</td>
                        <td style="color:#6b7280;font-size:.85rem;">
                            {{ \Illuminate\Support\Str::limit($data['message'] ?? '-', 60) }}
                        </td>
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
                            <div class="d-flex justify-content-center gap-1">
                                <!-- View Button -->
                                <button type="button" class="btn btn-sm btn-outline-info"
                                        title="View Full Message"
                                        onclick="viewNotif(
                                            '{{ addslashes($data['title'] ?? $log->description) }}',
                                            '{{ addslashes($data['message'] ?? 'N/A') }}',
                                            '{{ addslashes($data['sent_to'] ?? 'N/A') }}',
                                            '{{ $data['total_sent'] ?? 0 }}',
                                            '{{ $log->created_at->format('d M Y - h:i A') }}'
                                        )">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <!-- Delete Button -->
                                <form action="{{ route('notifications.history.destroy', $log->id) }}" method="POST"
                                      style="display:inline;" onsubmit="return confirm('Delete this notification?')">
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

    @if($logs->hasPages())
    <div class="card-footer d-flex justify-content-center" style="background:#f8fafc;border-top:1px solid #e5e7eb;padding:12px;">
        {{ $logs->links() }}
    </div>
    @endif
</div>

@else
<div class="card">
    <div class="card-body text-center py-5" style="color:#9ca3af;">
        <i class="fas fa-bell-slash fa-3x mb-3 d-block opacity-50"></i>
        <h5>No History Found</h5>
        <p>No notifications sent yet.</p>
    </div>
</div>
@endif

<!-- View Notification Modal -->
<div class="modal fade" id="viewNotifModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight:700;font-size:.95rem;">
                    <i class="fas fa-bell me-2 text-info"></i>
                    <span id="modal-title">Notification Details</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">

                <!-- Meta Info Row -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:4px;">Sent To</div>
                        <div id="modal-sent-to"></div>
                    </div>
                    <div class="col-sm-4">
                        <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:4px;">Total Recipients</div>
                        <div id="modal-total" style="font-size:1.4rem;font-weight:800;color:#111827;"></div>
                    </div>
                    <div class="col-sm-4">
                        <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:4px;">Date &amp; Time</div>
                        <div id="modal-date" style="font-size:.85rem;font-weight:600;color:#374151;"></div>
                    </div>
                </div>

                <!-- Message -->
                <div>
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:8px;">Full Message</div>
                    <div id="modal-message"
                         style="background:#f8fafc;border:1.5px solid #e5e7eb;border-radius:10px;padding:18px;color:#374151;font-size:.9rem;line-height:1.7;white-space:pre-wrap;min-height:80px;">
                    </div>
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
function viewNotif(title, message, sentTo, total, date) {
    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal-message').textContent = message;
    document.getElementById('modal-total').textContent   = total;
    document.getElementById('modal-date').textContent    = date;

    // Sent To badge
    var badgeMap = {
        'all':          '<span class="badge bg-success">All (Teachers & Students)</span>',
        'all_students': '<span class="badge bg-info">Students</span>',
        'all_teachers': '<span class="badge bg-warning text-dark">Teachers</span>',
    };
    document.getElementById('modal-sent-to').innerHTML = badgeMap[sentTo] || '<span class="badge bg-secondary">' + sentTo + '</span>';

    new bootstrap.Modal(document.getElementById('viewNotifModal')).show();
}
</script>
@endsection