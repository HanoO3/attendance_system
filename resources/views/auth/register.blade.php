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
        .auth-right { flex:1; display:flex; align-items:center; justify-content:center; padding:40px 20px; overflow-y:auto; }
        .register-box { width:100%; max-width:440px; }
        .register-box h2 { font-size:1.6rem; font-weight:800; color:#111827; margin-bottom:4px; }
        .register-box .sub { font-size:.875rem; color:#6b7280; margin-bottom:24px; }

        /* ---- Step Indicator ---- */
        .steps-wrap { display:flex; align-items:center; margin-bottom:28px; }
        .step-item { display:flex; align-items:center; gap:8px; }
        .step-circle {
            width:28px; height:28px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-size:.75rem; font-weight:700;
            border:2px solid #e5e7eb; color:#9ca3af; background:#fff;
            transition:all .25s; flex-shrink:0;
        }
        .step-circle.active { border-color:#6366f1; color:#6366f1; }
        .step-circle.done   { border-color:#6366f1; background:#6366f1; color:#fff; }
        .step-label { font-size:.75rem; font-weight:600; color:#9ca3af; transition:color .25s; }
        .step-label.active { color:#6366f1; }
        .step-label.done   { color:#6366f1; }
        .step-line { flex:1; height:2px; background:#e5e7eb; margin:0 10px; transition:background .25s; }
        .step-line.done { background:#6366f1; }

        /* ---- Step Panels ---- */
        .step-panel { display:none; }
        .step-panel.active { display:block; }

        /* ---- Role Cards (Step 1) ---- */
        .role-cards { display:flex; gap:12px; margin-bottom:10px; }
        .role-card {
            flex:1; padding:22px 12px; border:2px solid #e5e7eb; border-radius:12px;
            cursor:pointer; text-align:center; transition:all .2s; background:#fff;
        }
        .role-card:hover { border-color:#6366f1; background:rgba(99,102,241,.03); }
        .role-card.selected { border-color:#6366f1; background:rgba(99,102,241,.06); }
        .role-card-icon { font-size:30px; margin-bottom:10px; color:#9ca3af; transition:color .2s; }
        .role-card.selected .role-card-icon { color:#6366f1; }
        .role-card-title { font-size:.9rem; font-weight:700; color:#374151; margin-bottom:4px; transition:color .2s; }
        .role-card.selected .role-card-title { color:#6366f1; }
        .role-card-desc { font-size:.72rem; color:#9ca3af; line-height:1.4; }
        .role-error { font-size:.78rem; color:#ef4444; margin-top:4px; margin-bottom:10px; display:none; }

        /* ---- Fields (Step 2) ---- */
        .field-group { margin-bottom:15px; }
        .field-label { font-size:.78rem; font-weight:600; color:#6b7280; margin-bottom:5px; display:block; }
        .input-wrap { position:relative; }
        .input-wrap i.fi { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:14px; pointer-events:none; }
        .input-wrap input {
            width:100%; padding:11px 14px 11px 40px;
            border:1.5px solid #e5e7eb; border-radius:9px;
            font-family:'Outfit',sans-serif; font-size:.875rem; color:#111827;
            transition:border-color .15s, box-shadow .15s; background:#fff; outline:none;
        }
        .input-wrap input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.12); }
        .input-wrap input::placeholder { color:#cbd5e1; }
        .input-wrap input.is-invalid { border-color:#ef4444; }

        /* ---- Role Badge (Step 2) ---- */
        .role-badge {
            display:inline-flex; align-items:center; gap:7px;
            background:rgba(99,102,241,.08); color:#6366f1;
            border:1px solid rgba(99,102,241,.2); border-radius:20px;
            padding:5px 14px; font-size:.78rem; font-weight:600; margin-bottom:20px;
        }

        /* ---- Buttons ---- */
        .btn-continue {
            width:100%; padding:12px; background:#6366f1; color:#fff;
            border:none; border-radius:9px; font-size:.9rem; font-weight:700;
            font-family:'Outfit',sans-serif; cursor:pointer; transition:all .2s; margin-top:6px;
        }
        .btn-continue:hover { background:#4f46e5; transform:translateY(-1px); box-shadow:0 6px 20px rgba(99,102,241,.35); }
        .btn-register {
            width:100%; padding:12px; background:#6366f1; color:#fff;
            border:none; border-radius:9px; font-size:.9rem; font-weight:700;
            font-family:'Outfit',sans-serif; cursor:pointer; transition:all .2s; margin-top:4px;
        }
        .btn-register:hover { background:#4f46e5; transform:translateY(-1px); box-shadow:0 6px 20px rgba(99,102,241,.35); }
        .btn-back {
            width:100%; padding:11px; background:#fff; color:#6b7280;
            border:1.5px solid #e5e7eb; border-radius:9px;
            font-size:.875rem; font-weight:600; font-family:'Outfit',sans-serif;
            cursor:pointer; transition:all .2s; margin-bottom:8px;
        }
        .btn-back:hover { border-color:#6366f1; color:#6366f1; }

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

        {{-- Step Indicator --}}
        <div class="steps-wrap">
            <div class="step-item">
                <div class="step-circle active" id="circle1">1</div>
                <span class="step-label active" id="label1">Select Role</span>
            </div>
            <div class="step-line" id="line1"></div>
            <div class="step-item">
                <div class="step-circle" id="circle2">2</div>
                <span class="step-label" id="label2">Your Details</span>
            </div>
        </div>

        @if ($errors->any())
        <div class="error-box">
            <i class="fas fa-exclamation-circle me-1"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Hidden role field — JS isko fill karta hai --}}
            <input type="hidden" name="role" id="roleInput" value="{{ old('role','') }}">

            {{-- ========== STEP 1: Role Select ========== --}}
            <div class="step-panel active" id="step1">

                <div class="role-cards">

                    <div class="role-card {{ old('role')=='teacher' ? 'selected' : '' }}"
                         id="card_teacher" onclick="selectRole('teacher')">
                        <div class="role-card-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div class="role-card-title">Teacher</div>
                        <div class="role-card-desc">Mark attendance &amp; manage your classes</div>
                    </div>

                    <div class="role-card {{ old('role')=='student' ? 'selected' : '' }}"
                         id="card_student" onclick="selectRole('student')">
                        <div class="role-card-icon"><i class="fas fa-user-graduate"></i></div>
                        <div class="role-card-title">Student</div>
                        <div class="role-card-desc">View attendance &amp; submit leave requests</div>
                    </div>

                </div>

                <p class="role-error" id="roleError">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    Please select your role to continue.
                </p>

                <button type="button" class="btn-continue" onclick="goToStep2()">
                    Continue &nbsp;<i class="fas fa-arrow-right"></i>
                </button>

            </div>

            {{-- ========== STEP 2: Details ========== --}}
            <div class="step-panel" id="step2">

                <div class="role-badge" id="roleBadge">
                    <i class="fas fa-circle-check"></i>
                    <span id="badgeText"></span>
                </div>

                <div class="field-group">
                    <label class="field-label" id="labelName">Full Name</label>
                    <div class="input-wrap">
                        <i class="fas fa-user fi"></i>
                        <input type="text" name="name" id="inputName"
                               placeholder="Enter your full name" required
                               value="{{ old('name') }}"
                               class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" id="labelEmail">Email Address</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope fi"></i>
                        <input type="email" name="email" id="inputEmail"
                               placeholder="Enter your email" required
                               value="{{ old('email') }}"
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock fi"></i>
                        <input type="password" name="password"
                               placeholder="Create a password" required
                               class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Confirm Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-shield-check fi"></i>
                        <input type="password" name="password_confirmation"
                               placeholder="Repeat your password" required>
                    </div>
                </div>

                <button type="button" class="btn-back" onclick="goToStep1()">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </button>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus me-2"></i> Create Account
                </button>

            </div>

        </form>

        <div class="register-footer">
            Already have an account? <a href="{{ route('login') }}">Sign in here</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Role ke hisaab se labels
    const roleConfig = {
        teacher: {
            labelName:  'Teacher Full Name',
            phName:     'Enter teacher full name',
            labelEmail: 'Teacher Email Address',
            phEmail:    'Enter teacher email',
            badge:      'Registering as Teacher',
        },
        student: {
            labelName:  'Student Full Name',
            phName:     'Enter student full name',
            labelEmail: 'Student Email Address',
            phEmail:    'Enter student email',
            badge:      'Registering as Student',
        },
    };

    let currentRole = '{{ old("role","") }}';

    // Agar validation fail ho to old role restore karo
    if (currentRole) {
        highlightCard(currentRole);
        @if($errors->any())
            openStep2();
        @endif
    }

    function selectRole(role) {
        currentRole = role;
        document.getElementById('roleInput').value = role;
        document.getElementById('roleError').style.display = 'none';
        highlightCard(role);
    }

    function highlightCard(role) {
        document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
        const card = document.getElementById('card_' + role);
        if (card) card.classList.add('selected');
    }

    function goToStep2() {
        if (!currentRole) {
            document.getElementById('roleError').style.display = 'block';
            return;
        }
        openStep2();
    }

    function openStep2() {
        // Step indicator update
        const c1 = document.getElementById('circle1');
        c1.classList.remove('active');
        c1.classList.add('done');
        c1.innerHTML = '<i class="fas fa-check" style="font-size:10px"></i>';
        document.getElementById('label1').className = 'step-label done';
        document.getElementById('line1').classList.add('done');
        document.getElementById('circle2').classList.add('active');
        document.getElementById('label2').classList.add('active');

        // Panel switch
        document.getElementById('step1').classList.remove('active');
        document.getElementById('step2').classList.add('active');

        // Labels update
        const cfg = roleConfig[currentRole];
        document.getElementById('labelName').textContent  = cfg.labelName;
        document.getElementById('inputName').placeholder  = cfg.phName;
        document.getElementById('labelEmail').textContent = cfg.labelEmail;
        document.getElementById('inputEmail').placeholder = cfg.phEmail;
        document.getElementById('badgeText').textContent  = cfg.badge;

        setTimeout(() => document.getElementById('inputName').focus(), 100);
    }

    function goToStep1() {
        // Step indicator reset
        const c1 = document.getElementById('circle1');
        c1.classList.add('active');
        c1.classList.remove('done');
        c1.innerHTML = '1';
        document.getElementById('label1').className = 'step-label active';
        document.getElementById('line1').classList.remove('done');
        document.getElementById('circle2').classList.remove('active');
        document.getElementById('label2').classList.remove('active');

        // Panel switch
        document.getElementById('step2').classList.remove('active');
        document.getElementById('step1').classList.add('active');
    }
</script>
</body>
</html>