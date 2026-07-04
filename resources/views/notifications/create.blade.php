@extends('layouts.app')
@section('content')
<div style="margin-bottom:22px;">
    <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Send Notification</h1>
    <p style="font-size:.82rem;color:#6b7280;margin:0;">Send announcements to students and teachers</p>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="hico"><i class="fas fa-bell"></i></div>
                Compose Notification
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('notifications.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Send To</label>
                        <select name="recipient" class="form-select" style="background:#fff;border:1.5px solid #d1d5db;color:#111827;" required>
                            <option value="all">All Users (Students &amp; Teachers)</option>
                            <option value="all_students">All Students</option>
                            <option value="all_teachers">All Teachers</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title"
                               class="form-control"
                               style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                               placeholder="Enter notification title" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Message</label>
                        <textarea name="message" rows="6"
                                  class="form-control"
                                  style="background:#fff;border:1.5px solid #d1d5db;color:#111827;"
                                  placeholder="Write your message here..." required></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fas fa-paper-plane me-2"></i> Send Notification
                        </button>
                        <a href="{{ route('notifications.history') }}" class="btn btn-secondary">
                            <i class="fas fa-history me-1"></i> History
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection