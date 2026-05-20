<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AttendTrack') }} — Student Attendance Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --accent: #6366f1;
        --accent-dark: #4f46e5;
        --accent2: #8b5cf6;
        --txt: #111827;
        --txt2: #6b7280;
        --brd: #e5e7eb;
        --card: #ffffff;
        --bg: #f8fafc;
    }

    body {
        font-family: 'Outfit', sans-serif;
        background: var(--bg);
        color: var(--txt);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* ── NAVBAR ── */
    .navbar {
        position: sticky; top: 0; z-index: 100;
        background: rgba(255,255,255,.92);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--brd);
        padding: 0 40px;
        height: 64px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .nav-brand {
        display: flex; align-items: center; gap: 10px;
        text-decoration: none; color: var(--txt);
        font-size: 1.1rem; font-weight: 800;
    }
    .nav-logo {
        width: 36px; height: 36px;
        background: var(--accent);
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 16px;
    }
    .nav-links { display: flex; align-items: center; gap: 10px; }
    .btn-nav-login {
        padding: 8px 20px;
        border: 1.5px solid var(--brd);
        border-radius: 8px;
        background: transparent;
        color: var(--txt);
        font-family: 'Outfit', sans-serif;
        font-size: .88rem; font-weight: 600;
        text-decoration: none;
        transition: all .15s;
    }
    .btn-nav-login:hover { border-color: var(--accent); color: var(--accent); }
    .btn-nav-register {
        padding: 8px 20px;
        border: 1.5px solid var(--accent);
        border-radius: 8px;
        background: var(--accent);
        color: #fff;
        font-family: 'Outfit', sans-serif;
        font-size: .88rem; font-weight: 600;
        text-decoration: none;
        transition: all .15s;
    }
    .btn-nav-register:hover { background: var(--accent-dark); border-color: var(--accent-dark); color: #fff; }

    /* ── HERO ── */
    .hero {
        flex: 1;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        text-align: center;
        padding: 80px 24px 60px;
        position: relative; overflow: hidden;
    }
    .hero::before {
        content: '';
        position: absolute; top: -120px; left: 50%; transform: translateX(-50%);
        width: 700px; height: 700px;
        background: radial-gradient(circle, rgba(99,102,241,.08) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(99,102,241,.08);
        color: var(--accent);
        border: 1px solid rgba(99,102,241,.2);
        border-radius: 20px;
        padding: 5px 14px;
        font-size: .78rem; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase;
        margin-bottom: 24px;
    }
    .hero h1 {
        font-size: clamp(2.2rem, 5vw, 3.6rem);
        font-weight: 900;
        color: var(--txt);
        line-height: 1.12;
        margin-bottom: 18px;
        max-width: 700px;
    }
    .hero h1 .highlight {
        background: linear-gradient(135deg, var(--accent), var(--accent2));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .hero p {
        font-size: 1.05rem;
        color: var(--txt2);
        max-width: 520px;
        line-height: 1.7;
        margin-bottom: 36px;
    }
    .hero-btns {
        display: flex; gap: 12px; flex-wrap: wrap; justify-content: center;
        margin-bottom: 64px;
    }
    .btn-hero-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 13px 28px;
        background: var(--accent);
        color: #fff;
        border-radius: 10px;
        text-decoration: none;
        font-size: .95rem; font-weight: 700;
        transition: all .2s;
        box-shadow: 0 4px 14px rgba(99,102,241,.3);
    }
    .btn-hero-primary:hover { background: var(--accent-dark); transform: translateY(-1px); box-shadow: 0 8px 20px rgba(99,102,241,.4); color: #fff; }
    .btn-hero-outline {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 13px 28px;
        background: var(--card);
        color: var(--txt);
        border: 1.5px solid var(--brd);
        border-radius: 10px;
        text-decoration: none;
        font-size: .95rem; font-weight: 700;
        transition: all .2s;
    }
    .btn-hero-outline:hover { border-color: var(--accent); color: var(--accent); transform: translateY(-1px); }

    /* ── STATS ROW ── */
    .stats-row {
        display: flex; gap: 0;
        background: var(--card);
        border: 1px solid var(--brd);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,.05);
        margin-bottom: 80px;
    }
    .stat-item {
        flex: 1;
        padding: 20px 28px;
        border-right: 1px solid var(--brd);
        text-align: center;
    }
    .stat-item:last-child { border-right: none; }
    .stat-num { font-size: 1.6rem; font-weight: 900; color: var(--txt); }
    .stat-desc { font-size: .75rem; color: var(--txt2); font-weight: 500; margin-top: 3px; }

    /* ── FEATURES ── */
    .features-section {
        width: 100%; max-width: 960px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    .feat-card {
        background: var(--card);
        border: 1px solid var(--brd);
        border-radius: 14px;
        padding: 28px 24px;
        text-align: left;
        transition: all .2s;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }
    .feat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(0,0,0,.08); border-color: var(--accent); }
    .feat-icon {
        width: 44px; height: 44px;
        border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        margin-bottom: 16px;
        flex-shrink: 0;
    }
    .feat-icon.indigo { background: rgba(99,102,241,.1); color: var(--accent); }
    .feat-icon.green  { background: rgba(16,185,129,.1); color: #10b981; }
    .feat-icon.amber  { background: rgba(245,158,11,.1); color: #f59e0b; }
    .feat-icon.violet { background: rgba(139,92,246,.1); color: #8b5cf6; }
    .feat-icon.cyan   { background: rgba(6,182,212,.1);  color: #06b6d4; }
    .feat-icon.rose   { background: rgba(239,68,68,.1);  color: #ef4444; }
    .feat-card h5 { font-size: .95rem; font-weight: 700; color: var(--txt); margin-bottom: 6px; }
    .feat-card p  { font-size: .82rem; color: var(--txt2); line-height: 1.6; margin: 0; }

    /* ── FOOTER ── */
    footer {
        text-align: center;
        padding: 20px;
        border-top: 1px solid var(--brd);
        font-size: .8rem;
        color: var(--txt2);
        margin-top: auto;
    }

    @media(max-width: 768px) {
        .navbar { padding: 0 18px; }
        .features-section { grid-template-columns: 1fr; }
        .stats-row { flex-direction: column; }
        .stat-item { border-right: none; border-bottom: 1px solid var(--brd); }
        .stat-item:last-child { border-bottom: none; }
    }
    @media(max-width: 500px) {
        .features-section { grid-template-columns: 1fr; }
    }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="{{ url('/') }}" class="nav-brand">
        <div class="nav-logo"><i class="fas fa-graduation-cap"></i></div>
        AttendTrack
    </a>
    <div class="nav-links">
        <a href="{{ route('login') }}" class="btn-nav-login">Login</a>
        <a href="{{ route('register') }}" class="btn-nav-register">Register</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-badge">
        <i class="fas fa-shield-check"></i> Trusted Attendance Platform
    </div>
    <h1>Smart <span class="highlight">Attendance</span><br>Management System</h1>
    <p>Manage students, track attendance, approve leaves and generate detailed reports — all in one clean portal.</p>

    <div class="hero-btns">
        <a href="{{ route('login') }}" class="btn-hero-primary">
            <i class="fas fa-sign-in-alt"></i> Sign In Now
        </a>
        <a href="{{ route('register') }}" class="btn-hero-outline">
            <i class="fas fa-user-plus"></i> Create Account
        </a>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-item">
            <div class="stat-num">3 Roles</div>
            <div class="stat-desc">Admin, Teacher, Student</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">Real-time</div>
            <div class="stat-desc">Attendance Tracking</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">PDF</div>
            <div class="stat-desc">Report Generation</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">Leave</div>
            <div class="stat-desc">Request & Approval</div>
        </div>
    </div>

    <!-- Features -->
    <div class="features-section">
        <div class="feat-card">
            <div class="feat-icon indigo"><i class="fas fa-clipboard-check"></i></div>
            <h5>Mark Attendance</h5>
            <p>Quickly mark present, absent or late for each student per subject and semester.</p>
        </div>
        <div class="feat-card">
            <div class="feat-icon green"><i class="fas fa-chart-bar"></i></div>
            <h5>Detailed Reports</h5>
            <p>Generate and export PDF attendance reports by student, department or subject.</p>
        </div>
        <div class="feat-card">
            <div class="feat-icon amber"><i class="fas fa-calendar-check"></i></div>
            <h5>Leave Management</h5>
            <p>Students submit leave requests; admins approve or reject them with one click.</p>
        </div>
        <div class="feat-card">
            <div class="feat-icon violet"><i class="fas fa-users-cog"></i></div>
            <h5>User Management</h5>
            <p>Manage students, teachers, departments and subjects from one central panel.</p>
        </div>
        <div class="feat-card">
            <div class="feat-icon cyan"><i class="fas fa-bell"></i></div>
            <h5>Notifications</h5>
            <p>Send announcements to students and teachers directly through the portal.</p>
        </div>
        <div class="feat-card">
            <div class="feat-icon rose"><i class="fas fa-history"></i></div>
            <h5>Activity Logs</h5>
            <p>Super admins can view complete activity history across the entire system.</p>
        </div>
    </div>
</section>

<footer>
    &copy; {{ date('Y') }} AttendTrack. All rights reserved.
</footer>

</body>
</html>
