@extends('layouts.app')

@section('content')

<div style="margin-bottom:22px;">
    <h1 style="font-size:1.35rem;font-weight:800;color:#111827;margin-bottom:3px;">Dashboard</h1>
    <p style="font-size:.82rem;color:#6b7280;margin:0;">Welcome to AttendTrack Portal</p>
</div>

@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body text-center py-5">
        <div style="width:64px;height:64px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:28px;margin:0 auto 16px;">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h4 style="font-weight:800;color:#111827;margin-bottom:8px;">You are logged in!</h4>
        <p style="color:#6b7280;margin-bottom:20px;">Redirecting you to your dashboard...</p>
        @auth
            @if(auth()->user()->role == 'student')
                <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i> Go to My Dashboard
                </a>
            @elseif(auth()->user()->role == 'teacher')
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i> Go to Dashboard
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i> Go to Dashboard
                </a>
            @endif
        @endauth
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Auto redirect after 1 second
    setTimeout(function() {
        @auth
            @if(auth()->user()->role == 'student')
                window.location.href = "{{ route('student.dashboard') }}";
            @elseif(auth()->user()->role == 'teacher')
                window.location.href = "{{ route('teacher.dashboard') }}";
            @else
                window.location.href = "{{ route('dashboard') }}";
            @endif
        @endauth
    }, 1000);
</script>
@endsection