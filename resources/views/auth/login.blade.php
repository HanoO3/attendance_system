<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login &mdash; {{ config('app.name', 'AttendTrack') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        *{box-sizing:border-box;}
        body{font-family:'Plus Jakarta Sans',sans-serif;margin:0;padding:0;min-height:100vh;display:flex;background:#f1f5f9;}
        .split-left{width:45%;background:linear-gradient(145deg,#0f172a 0%,#1e3a5f 50%,#1d4ed8 100%);display:flex;flex-direction:column;justify-content:space-between;padding:48px;position:relative;overflow:hidden;}
        .split-left::before{content:'';position:absolute;top:-60px;right:-60px;width:300px;height:300px;background:rgba(59,130,246,.15);border-radius:50%;pointer-events:none;}
        .split-left::after{content:'';position:absolute;bottom:-80px;left:-40px;width:250px;height:250px;background:rgba(139,92,246,.1);border-radius:50%;pointer-events:none;}
        .split-right{flex:1;display:flex;align-items:center;justify-content:center;padding:40px 20px;}
        .brand-wrap{position:relative;z-index:1;}
        .brand-ico{width:48px;height:48px;background:rgba(255,255,255,.15);border-radius:14px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;margin-bottom:16px;backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.2);}
        .brand-name{font-size:1.6rem;font-weight:800;color:#fff;margin-bottom:6px;}
        .brand-desc{font-size:.9rem;color:rgba(255,255,255,.6);line-height:1.6;}
        .feat-list{list-style:none;padding:0;margin:0;position:relative;z-index:1;}
        .feat-list li{display:flex;align-items:center;gap:12px;color:rgba(255,255,255,.75);font-size:.875rem;font-weight:500;padding:8px 0;}
        .feat-list li i{width:28px;height:28px;background:rgba(255,255,255,.12);border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:12px;color:#fff;flex-shrink:0;}
        .login-card{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:40px;width:100%;max-width:420px;box-shadow:0 4px 24px rgba(0,0,0,.06);}
        .login-card h2{font-size:1.5rem;font-weight:800;color:#0f172a;margin-bottom:4px;}
        .login-card .sub{font-size:.875rem;color:#64748b;margin-bottom:32px;}
        .form-group{margin-bottom:18px;}
        .form-label{font-size:.8rem;font-weight:600;color:#64748b;margin-bottom:6px;display:block;}
        .input-wrap{position:relative;}
        .input-wrap i{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:14px;pointer-events:none;}
        .input-wrap input{width:100%;padding:11px 14px 11px 40px;border:1.5px solid #e2e8f0;border-radius:9px;font-family:inherit;font-size:.875rem;color:#0f172a;transition:border-color .15s,box-shadow .15s;background:#fff;outline:none;}
        .input-wrap input:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.12);}
        .input-wrap input::placeholder{color:#cbd5e1;}
        .btn-login{width:100%;padding:12px;background:#3b82f6;color:#fff;border:none;border-radius:9px;font-size:.9rem;font-weight:700;font-family:inherit;cursor:pointer;transition:all .15s;margin-top:8px;}
        .btn-login:hover{background:#1d4ed8;transform:translateY(-1px);box-shadow:0 6px 20px rgba(59,130,246,.35);}
        .remember-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;}
        .remember-row label{display:flex;align-items:center;gap:8px;font-size:.82rem;color:#64748b;cursor:pointer;}
        .remember-row input[type=checkbox]{width:16px;height:16px;accent-color:#3b82f6;cursor:pointer;}
        .remember-row a{font-size:.82rem;color:#3b82f6;text-decoration:none;font-weight:600;}
        .remember-row a:hover{text-decoration:underline;}
        .login-footer{text-align:center;margin-top:24px;font-size:.82rem;color:#64748b;}
        .login-footer a{color:#3b82f6;text-decoration:none;font-weight:600;}
        @media(max-width:767px){.split-left{display:none;} .split-right{background:#f1f5f9;}}
    </style>
</head>
<body>
    <div class="split-left">
        <div class="brand-wrap">
            <div class="brand-ico"><i class="fas fa-graduation-cap"></i></div>
            <div class="brand-name">AttendTrack</div>
            <div class="brand-desc">A complete student attendance management system for educational institutions.</div>
        </div>
        <ul class="feat-list">
            <li><i class="fas fa-clipboard-check"></i> Real-time attendance marking</li>
            <li><i class="fas fa-chart-bar"></i> Detailed reports & analytics</li>
            <li><i class="fas fa-bell"></i> Smart notifications system</li>
            <li><i class="fas fa-calendar-check"></i> Leave management workflow</li>
        </ul>
    </div>
    <div class="split-right">
        <div class="login-card">
            <h2>Welcome back</h2>
            <p class="sub">Sign in to your portal to continue</p>

            @if ($errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:.85rem;color:#dc2626;">
                <i class="fas fa-exclamation-circle me-1"></i>
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email or Roll Number</label>
                    <div class="input-wrap">
                        <i class="fas fa-user"></i>
                        <input type="text" name="email" placeholder="Enter email or roll number" required autofocus value="{{ old('email') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>
                <div class="remember-row">
                    <label>
                        <input type="checkbox" name="remember" id="remember">
                        Remember me
                    </label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i> Sign In
                </button>
            </form>

            <div class="login-footer">
                Don't have an account? <a href="{{ route('register') }}">Create one</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
