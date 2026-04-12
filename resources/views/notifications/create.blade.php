@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Send Notification</h2>
            <p class="text-light opacity-75 mb-0">Send announcements to students and teachers</p>
        </div>
    </div>

    <!-- Notification Card -->
    <div class="card border-light shadow">
        <div class="card-body">

            <form method="POST" action="{{ route('notifications.store') }}">
                @csrf

                <!-- 1. Send To -->
                <div class="mb-3">
                    <label for="recipient" class="form-label" style="color: #ffffff;">Send To</label>
                    <select name="recipient" id="recipient" class="form-select bg-transparent text-white border-light custom-select-dark" required>
                        <option value="all">All Users (Students & Teachers)</option>
                        <option value="all_students">All Students</option>
                        <option value="all_teachers">All Teachers</option>
                    </select>
                </div>

                <!-- 2. Title -->
                <div class="mb-3">
                    <label for="title" class="form-label" style="color: #ffffff;">Title</label>
                    <input type="text" name="title" id="title" class="form-control bg-transparent text-white border-light" placeholder="Enter notification title" required>
                </div>

                <!-- 3. Message -->
                <div class="mb-4">
                    <label for="message" class="form-label" style="color: #ffffff;">Message</label>
                    <textarea name="message" id="message" rows="5" class="form-control bg-transparent text-white border-light" placeholder="Write your message here..." required></textarea>
                </div>

                <!-- Centered Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="fas fa-paper-plane me-2"></i> Send Notification
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
    .form-control::placeholder { color: rgba(255, 255, 255, 0.6); }
</style>
@endsection