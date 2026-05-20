<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register &mdash; {{ config('app.name', 'AttendTrack') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f8fafc;
        }
        /* LEFT PANEL */
        .auth-left {
            width: 42%;
            background: linear-gradient(145deg, #0f172a 0%, #1e1b4b 45%, #312e81 100%);
            display: flex; flex-direction: column; justify-content: space-between;
            padding: 48px; position: relative; overflow: hidden;
        }
        .auth-left::before { content:''; position:absolute; top:-80px; right:-80px; width:320px; height:320px; background:rgba(99,102,241,.15); border-radius:50%; pointer-events:none; }
        .auth-left::after  { content:''; position:absolute; bottom:-100px; left:-60px; width:280px; height:280px; background:rgba(139,92,246,.1); border-radius:50%; pointer-events:none; }
        .brand-wrap { position: relative; z-index: 1; }
        .brand-icon { width:48px; height:48px; background:rgba(255,255,255,.12); border-radius:14px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:22px; margin-bottom:18px; backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,.15); }
        .brand-name { font-size:1.7rem; font-weight:800; color:#fff; margin-bottom:8px; }
        .brand-tagline { font-size:.9rem; color:rgba(255,255,255,.55); line-height:1.65; max-width:280px; }
        .feat-list { list-style:none; position:relative; z-index:1; }
        .feat-list li { display:flex; align-items:center; gap:12px; color:rgba(255,255,255,.7); font-size:.875rem; font-weight:500; padding:9px 0; border-bottom:1px solid rgba(255,255,255,.05); }
        .feat-list li:last-child { border-bottom:none; }
        .feat-icon { width:30px; height:30px; background:rgba(255,255,255,.1); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:12px; color:#a5b4fc; flex-shrink:0; }
        /* RIGHT PANEL */
        .auth-right { flex:1; display:flex; align-items:center; justify-content:center; padding:40px 20px; }
        .register-box { width:100%; max-width:440px; }
        .register-box h2 { font-size:1.6rem; font-weight:800; color:#111827; margin-bottom:4px; }
        .register-box .sub { font-size:.875rem; color:#6b7280; margin-bottom:28px; }
        .field-group { margin-bottom:15px; }
        .field-label { font-size:.78rem; font-weight:600; color:#6b7280; margin-bottom:5px; display:block; }
        .input-wrap { position:relative; }
        .input-wrap i { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:14px; pointer-events:none; }
        .input-wrap input {
            width:100%; padding:11px 14px 11px 40px;
            border:1.5px solid #e5e7eb; border-radius:9px;
            font-family:'Outfit',sans-serif; font-size:.875rem; color:#111827;
            transition:border-color .15s, box-shadow .15s; background:#fff; outline:none;
        }
        .input-wrap input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.12); }
        .input-wrap input::placeholder { color:#cbd5e1; }
        .input-wrap input.is-invalid { border-color:#ef4444; }
        .role-group { margin-bottom:18px; }
        .role-label { font-size:.78rem; font-weight:600; color:#6b7280; margin-bottom:8px; display:block; }
        .role-options { display:flex; gap:8px; }
        .role-opt { flex:1; }
        .role-opt input[type=radio] { display:none; }
        .role-opt label {
            display:flex; flex-direction:column; align-items:center; gap:4px;
            padding:10px 8px; border:1.5px solid #e5e7eb; border-radius:9px;
            cursor:pointer; transition:all .15s; font-size:.78rem; font-weight:600; color:#6b7280;
            text-align:center;
        }
        .role-opt label i { font-size:16px; }
        .role-opt input[type=radio]:checked + label { border-color:#6366f1; background:rgba(99,102,241,.06); color:#6366f1; }
        .role-opt label:hover { border-color:#6366f1; color:#6366f1; }
        .btn-register {
            width:100%; padding:12px;
            background:#6366f1; color:#fff; border:none; border-radius:9px;
            font-size:.9rem; font-weight:700; font-family:'Outfit',sans-serif;
            cursor:pointer; transition:all .2s; margin-top:4px;
        }
        .btn-register:hover { background:#4f46e5; transform:translateY(-1px); box-shadow:0 6px 20px rgba(99,102,241,.35); }
        .register-footer { text-align:center; margin-top:20px; font-size:.82rem; color:#6b7280; }
        .register-footer a { color:#6366f1; text-decoration:none; font-weight:600; }
        .register-footer a:hover { text-decoration:underline; }
        .error-box { background:#fef2f2; border:1px solid #fecaca; border-radius:9px; padding:12px 16px; margin-bottom:16px; font-size:.84rem; color:#dc2626; }
        @media(max-width:768px) { .auth-left { display:none; } .auth-right { background:#f8fafc; } }
    </style>
</head>
<body>
    <div class="auth-left">
        <div class="brand-wrap">
            <div class="brand-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="brand-name">AttendTrack</div>
            <div class="brand-tagline">Join the platform used by students, teachers and admins to manage attendance seamlessly.</div>
        </div>
        <ul class="feat-list">
            <li><div class="feat-icon"><i class="fas fa-clipboard-check"></i></div> Real-time attendance marking</li>
            <li><div class="feat-icon"><i class="fas fa-chart-bar"></i></div> Detailed reports &amp; analytics</li>
            <li><div class="feat-icon"><i class="fas fa-bell"></i></div> Smart notification system</li>
            <li><div class="feat-icon"><i class="fas fa-calendar-check"></i></div> Leave management workflow</li>
        </ul>
    </div>

    <div class="auth-right">
        <div class="register-box">
            <h2>Create Account</h2>
            <p class="sub">Fill in the details below to get started</p>

            @if ($errors->any())
            <div class="error-box">
                <i class="fas fa-exclamation-circle me-1"></i>
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="field-group">
                    <label class="field-label">Full Name</label>
                    <div class="input-wrap">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" placeholder="Enter your full name" required value="{{ old('name') }}" class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Email Address</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Enter your email" required value="{{ old('email') }}" class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Create a password" required class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Confirm Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-shield-check"></i>
                        <input type="password" name="password_confirmation" placeholder="Repeat your password" required>
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="role-group">
                    <span class="role-label">Register as</span>
                    <div class="role-options">
                        <div class="role-opt">
                            <input type="radio" name="role" id="role_admin" value="admin" {{ old('role') == 'admin' ? 'checked' : '' }}>
                            <label for="role_admin">
                                <i class="fas fa-user-shield"></i> Admin
                            </label>
                        </div>
                        <div class="role-opt">
                            <input type="radio" name="role" id="role_teacher" value="teacher" {{ old('role') == 'teacher' ? 'checked' : '' }}>
                            <label for="role_teacher">
                                <i class="fas fa-chalkboard-teacher"></i> Teacher
                            </label>
                        </div>
                        <div class="role-opt">
                            <input type="radio" name="role" id="role_student" value="student" {{ old('role','student') == 'student' ? 'checked' : '' }}>
                            <label for="role_student">
                                <i class="fas fa-user-graduate"></i> Student
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus me-2"></i> Create Account
                </button>
            </form>

            <div class="register-footer">
                Already have an account? <a href="{{ route('login') }}">Sign in here</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
