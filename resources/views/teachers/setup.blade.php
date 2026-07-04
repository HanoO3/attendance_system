@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width:600px; margin:auto;">

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">

            <h4 class="mb-1 fw-bold">Complete Your Profile</h4>
            <p class="text-muted mb-4">
                Fill in your details. Admin will review and assign your department and subjects.
            </p>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                </div>
            @endif

            {{-- Already request bheji hai — pending --}}
            @if($existingRequest && $existingRequest->status === 'pending')
                <div class="alert alert-warning">
                    <i class="fas fa-clock me-2"></i>
                    <strong>Request Submitted!</strong><br>
                    Your profile setup request is pending. Please wait for admin approval.
                </div>

            {{-- Reject ho gayi — dobara bhejna --}}
            @elseif($existingRequest && $existingRequest->status === 'rejected')
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-times-circle me-2"></i>
                    Your previous request was rejected. You can submit again.
                </div>
                {{-- Form dikhao --}}
                @include('teachers._setup_form', ['sessions' => $sessions, 'departments' => $departments, 'semesters' => $semesters])

            {{-- Koi request nahi — form dikhao --}}
            @else
                @include('teachers._setup_form', ['sessions' => $sessions, 'departments' => $departments, 'semesters' => $semesters])
            @endif

        </div>
    </div>

</div>
@endsection
