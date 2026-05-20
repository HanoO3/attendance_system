<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AttendTrack') }} | Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
    :root{--sidebar-bg:#0f172a;--sidebar-w:260px;--accent:#3b82f6;--accent-d:#1d4ed8;--body-bg:#f1f5f9;--card:#ffffff;--txt:#0f172a;--txt2:#64748b;--brd:#e2e8f0;--bar:64px;}
    *{box-sizing:border-box;}
    body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--body-bg);color:var(--txt);margin:0;padding:0;}
    /* SIDEBAR */
    .sidebar{position:fixed;top:0;left:0;width:var(--sidebar-w);height:100vh;background:var(--sidebar-bg);display:flex;flex-direction:column;z-index:1040;transition:transform .3s;overflow:hidden;}
    .sb-brand{display:flex;align-items:center;gap:12px;padding:0 20px;height:var(--bar);border-bottom:1px solid rgba(255,255,255,.06);text-decoration:none;flex-shrink:0;}
    .sb-brand .bicon{width:36px;height:36px;background:var(--accent);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;flex-shrink:0;}
    .sb-brand .btxt{font-size:1rem;font-weight:700;color:#fff;line-height:1.2;}
    .sb-brand .bsub{font-size:.6rem;color:#94a3b8;font-weight:400;letter-spacing:.08em;text-transform:uppercase;}
    .sb-nav{flex:1;overflow-y:auto;padding:16px 0;}
    .sb-nav::-webkit-scrollbar{width:3px;} .sb-nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,.1);border-radius:3px;}
    .nav-lbl{font-size:.6rem;letter-spacing:.12em;text-transform:uppercase;color:rgba(148,163,184,.5);font-weight:600;padding:16px 20px 6px;}
    .sb-link{display:flex;align-items:center;gap:12px;padding:9px 20px;color:#94a3b8;text-decoration:none;font-size:.875rem;font-weight:500;transition:all .15s;position:relative;margin:1px 10px;border-radius:8px;}
    .sb-link:hover{background:rgba(255,255,255,.05);color:#fff;}
    .sb-link.active{background:rgba(59,130,246,.15);color:#fff;}
    .sb-link.active::before{content:'';position:absolute;left:-10px;top:50%;transform:translateY(-50%);width:3px;height:20px;background:var(--accent);border-radius:0 3px 3px 0;}
    .nav-ico{width:32px;height:32px;display:flex;align-items:center;justify-content:center;border-radius:7px;font-size:13px;background:rgba(255,255,255,.04);flex-shrink:0;transition:background .15s;}
    .sb-link.active .nav-ico{background:var(--accent);color:#fff;}
    .sb-link:hover .nav-ico{background:rgba(255,255,255,.08);}
    .nav-bdg{margin-left:auto;background:#ef4444;color:#fff;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:20px;line-height:1.4;}
    .sb-foot{padding:16px 20px;border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;}
    .sb-user{display:flex;align-items:center;gap:10px;}
    .sb-user .av{width:36px;height:36px;background:linear-gradient(135deg,var(--accent),#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;flex-shrink:0;}
    .sb-user .uname{font-size:.8rem;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .sb-user .urole{font-size:.65rem;color:#94a3b8;text-transform:capitalize;}
    .sb-user .logout-btn{color:#94a3b8;transition:color .15s;cursor:pointer;border:none;background:none;padding:4px;border-radius:4px;margin-left:auto;}
    .sb-user .logout-btn:hover{color:#ef4444;}
    /* MAIN */
    .mw{margin-left:var(--sidebar-w);min-height:100vh;display:flex;flex-direction:column;transition:margin-left .3s;}
    /* TOPBAR */
    .topbar{height:var(--bar);background:var(--card);border-bottom:1px solid var(--brd);display:flex;align-items:center;padding:0 24px;position:sticky;top:0;z-index:1030;gap:16px;}
    .top-toggle{display:none;width:36px;height:36px;border:1px solid var(--brd);border-radius:8px;background:none;align-items:center;justify-content:center;cursor:pointer;color:var(--txt2);flex-shrink:0;}
    .top-bc{flex:1;font-size:.85rem;color:var(--txt2);font-weight:500;}
    .top-bc strong{color:var(--txt);font-weight:700;}
    .top-pill{display:flex;align-items:center;gap:8px;padding:6px 14px 6px 10px;border:1px solid var(--brd);border-radius:40px;background:var(--body-bg);color:var(--txt);text-decoration:none;font-size:.82rem;font-weight:600;cursor:pointer;transition:all .15s;}
    .top-pill:hover{border-color:var(--accent);color:var(--accent);}
    .top-av{width:24px;height:24px;background:linear-gradient(135deg,var(--accent),#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:10px;font-weight:700;}
    /* PAGE */
    .pg{flex:1;padding:28px;}
    /* CARDS */
    .card{background:var(--card);border:1px solid var(--brd);border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.04);color:var(--txt);margin-bottom:20px;}
    .card-header{background:transparent;border-bottom:1px solid var(--brd);padding:16px 20px;font-weight:700;font-size:.9rem;color:var(--txt);display:flex;align-items:center;gap:8px;}
    .card-header .hico{width:28px;height:28px;background:rgba(59,130,246,.1);color:var(--accent);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:12px;}
    /* STAT CARDS */
    .stat-card{background:var(--card);border:1px solid var(--brd);border-radius:12px;padding:20px;display:flex;align-items:center;gap:16px;text-decoration:none;transition:all .2s;box-shadow:0 1px 3px rgba(0,0,0,.04);}
    .stat-card:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(0,0,0,.08);border-color:var(--accent);text-decoration:none;}
    .st-ico{width:52px;height:52px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
    .st-ico.blue{background:rgba(59,130,246,.1);color:#3b82f6;}
    .st-ico.green{background:rgba(16,185,129,.1);color:#10b981;}
    .st-ico.amber{background:rgba(245,158,11,.1);color:#f59e0b;}
    .st-ico.violet{background:rgba(139,92,246,.1);color:#8b5cf6;}
    .st-ico.rose{background:rgba(239,68,68,.1);color:#ef4444;}
    .st-ico.cyan{background:rgba(6,182,212,.1);color:#06b6d4;}
    .st-lbl{font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em;color:var(--txt2);margin-bottom:4px;}
    .st-val{font-size:1.6rem;font-weight:800;color:var(--txt);line-height:1;}
    /* PAGE HEADER */
    .pg-hdr{margin-bottom:24px;}
    .pg-hdr h2{font-size:1.4rem;font-weight:800;color:var(--txt);margin:0 0 4px;}
    .pg-hdr p{font-size:.85rem;color:var(--txt2);margin:0;}
    /* TABLE */
    .table{color:var(--txt);font-size:.875rem;}
    .table thead th{background:var(--body-bg);color:var(--txt2);font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;border-bottom:1px solid var(--brd);padding:12px 16px;}
    .table tbody tr{transition:background .1s;}
    .table tbody tr:hover{background:rgba(59,130,246,.03);}
    .table tbody td{padding:13px 16px;border-bottom:1px solid var(--brd);vertical-align:middle;color:var(--txt);}
    .table tbody tr:last-child td{border-bottom:none;}
    /* FORM */
    .form-control,.form-select{background:var(--card);border:1.5px solid var(--brd);color:var(--txt);font-size:.875rem;border-radius:8px;padding:9px 14px;transition:border-color .15s,box-shadow .15s;}
    .form-control:focus,.form-select:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(59,130,246,.12);background:var(--card);color:var(--txt);}
    .form-label{font-size:.8rem;font-weight:600;color:var(--txt2);margin-bottom:6px;}
    /* BTN */
    .btn{font-size:.85rem;font-weight:600;border-radius:8px;padding:8px 16px;transition:all .15s;}
    .btn-primary{background:var(--accent);border-color:var(--accent);}
    .btn-primary:hover{background:var(--accent-d);border-color:var(--accent-d);}
    .btn-sm{padding:5px 12px;font-size:.78rem;}
    /* BADGE */
    .badge{font-weight:600;font-size:.7rem;border-radius:6px;padding:4px 8px;}
    /* DROPDOWN */
    .dropdown-menu{border:1px solid var(--brd);border-radius:10px;box-shadow:0 10px 30px rgba(0,0,0,.1);padding:6px;font-size:.85rem;}
    .dropdown-item{border-radius:6px;padding:8px 12px;color:var(--txt);font-weight:500;}
    .dropdown-item:hover{background:var(--body-bg);}
    /* MODAL */
    .modal-content{border:1px solid var(--brd);border-radius:16px;box-shadow:0 25px 60px rgba(0,0,0,.15);}
    .modal-header{border-bottom:1px solid var(--brd);padding:18px 24px;}
    .modal-footer{border-top:1px solid var(--brd);padding:16px 24px;}
    /* ALERT */
    .alert{border-radius:10px;border:none;font-size:.875rem;}
    /* CUSTOM SELECT */
    .custom-select-dark option{background-color:#fff !important;color:#000 !important;}
    /* QUICK ACTION */
    .qa-item{display:flex;align-items:center;gap:14px;padding:14px 20px;border-bottom:1px solid var(--brd);text-decoration:none;color:var(--txt);transition:background .15s;}
    .qa-item:last-child{border-bottom:none;}
    .qa-item:hover{background:rgba(59,130,246,.04);color:var(--txt);text-decoration:none;}
    .qa-ico{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;}
    .qa-lbl{font-weight:600;font-size:.875rem;color:var(--txt);}
    .qa-sub{font-size:.73rem;color:var(--txt2);}
    /* RESPONSIVE */
    @media(max-width:991px){
        .sidebar{transform:translateX(-100%);}
        .sidebar.open{transform:translateX(0);box-shadow:0 0 0 100vmax rgba(0,0,0,.4);}
        .mw{margin-left:0;}
        .top-toggle{display:flex;}
    }
    .toast-top-right{top:80px;right:20px;}
    ::-webkit-scrollbar{width:6px;height:6px;}
    ::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:6px;}
    @media print{.sidebar,.topbar{display:none;} .mw{margin-left:0;} .pg{padding:0;}}
    </style>
</head>
<body>
@auth
<aside class="sidebar" id="mainSidebar">
    <a href="{{ url('/') }}" class="sb-brand">
        <div class="bicon"><i class="fas fa-graduation-cap"></i></div>
        <div><div class="btxt">AttendTrack</div><div class="bsub">Management Portal</div></div>
    </a>
    <nav class="sb-nav">
    @if(in_array(auth()->user()->role, ['admin', 'teacher', 'super_admin']))
        <div class="nav-lbl">Overview</div>
        <a href="{{ auth()->user()->role == 'teacher' ? route('teacher.dashboard') : route('dashboard') }}"
           class="sb-link {{ (request()->is('dashboard') || request()->is('teacher/dashboard')) ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-chart-pie"></i></div> Dashboard
        </a>
        <div class="nav-lbl">Management</div>
        <a href="{{ route('students.index') }}" class="sb-link {{ request()->is('students*') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-user-graduate"></i></div> Students
        </a>
        @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
        <a href="{{ route('teachers.index') }}" class="sb-link {{ request()->is('teachers*') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-chalkboard-teacher"></i></div> Teachers
        </a>
        <a href="{{ route('departments.index') }}" class="sb-link {{ request()->is('departments*') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-building"></i></div> Departments
        </a>
        <a href="{{ route('subjects.index') }}" class="sb-link {{ request()->is('subjects*') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-book-open"></i></div> Subjects
        </a>
        @endif
        <div class="nav-lbl">Attendance</div>
        <a href="{{ route('attendance.index') }}" class="sb-link {{ request()->is('attendance*') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-clipboard-check"></i></div> Mark Attendance
        </a>
        <a href="{{ route('reports.index') }}" class="sb-link {{ request()->is('reports*') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-chart-bar"></i></div> Reports
        </a>
        @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
        <div class="nav-lbl">Admin</div>
        <a href="{{ route('notifications.history') }}" class="sb-link {{ request()->is('notifications*') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-bell"></i></div> Notifications
        </a>
        <a href="{{ route('admin.requests') }}" class="sb-link {{ request()->is('profile-requests') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-inbox"></i></div> Profile Requests
            @if(isset($pendingProfileCount) && $pendingProfileCount > 0)<span class="nav-bdg">{{ $pendingProfileCount }}</span>@endif
        </a>
        <a href="{{ route('admin.leaves') }}" class="sb-link {{ request()->is('leave-requests') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-calendar-check"></i></div> Leave Approvals
            @if(isset($pendingLeavesCount) && $pendingLeavesCount > 0)<span class="nav-bdg">{{ $pendingLeavesCount }}</span>@endif
        </a>
        @endif
        @if(auth()->user()->role == 'teacher')
        <a href="{{ route('teacher.notifications') }}" class="sb-link {{ request()->is('teacher/notifications*') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-bell"></i></div> Notifications
            @if(isset($teacherNotifBadge) && $teacherNotifBadge > 0)<span class="nav-bdg">{{ $teacherNotifBadge }}</span>@endif
        </a>
        @endif
        @if(auth()->user()->role == 'super_admin')
        <a href="{{ route('activity-logs.index') }}" class="sb-link {{ request()->is('activity-logs*') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-history"></i></div> Activity Logs
            @if(isset($adminActivityBadge) && $adminActivityBadge > 0)<span class="nav-bdg">{{ $adminActivityBadge }}</span>@endif
        </a>
        @endif
    @else
        <div class="nav-lbl">My Portal</div>
        <a href="{{ route('student.dashboard') }}" class="sb-link {{ request()->is('my-dashboard') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-home"></i></div> My Dashboard
        </a>
        <a href="{{ route('student.attendance') }}" class="sb-link {{ request()->is('my-attendance') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-clipboard-list"></i></div> My Attendance
        </a>
        <a href="{{ route('student.leave') }}" class="sb-link {{ request()->is('my-leave') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-envelope-open-text"></i></div> Leave Request
            @if(isset($studentLeaveBadge) && $studentLeaveBadge > 0)<span class="nav-bdg">{{ $studentLeaveBadge }}</span>@endif
        </a>
        <a href="{{ route('student.notifications') }}" class="sb-link {{ request()->is('my-notifications') ? 'active' : '' }}">
            <div class="nav-ico"><i class="fas fa-bell"></i></div> Notifications
            @if(isset($studentNotifBadge) && $studentNotifBadge > 0)<span class="nav-bdg">{{ $studentNotifBadge }}</span>@endif
        </a>
    @endif
    </nav>
    <div class="sb-foot">
        <div class="sb-user">
            <div class="av">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div style="flex:1;min-width:0;">
                <div class="uname">{{ auth()->user()->name }}</div>
                <div class="urole">{{ auth()->user()->role }}</div>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            <button class="logout-btn" onclick="event.preventDefault();document.getElementById('logout-form').submit();" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
    </div>
</aside>
@endauth

<div class="mw" id="mainWrapper">
    <header class="topbar">
        @auth
        <button class="top-toggle" id="sidebarToggle" type="button">
            <i class="fas fa-bars"></i>
        </button>
        @endauth
        <div class="top-bc">
            @auth
            <strong>{{ ucfirst(auth()->user()->role) }} Panel</strong>
            <span style="color:#e2e8f0;"> &rsaquo; </span>
            {{ ucwords(str_replace(['-','.'], [' ', ' › '], request()->path())) }}
            @endauth
        </div>
        @auth
        <div class="dropdown">
            <div class="top-pill dropdown-toggle" data-bs-toggle="dropdown" role="button">
                <div class="top-av">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                {{ auth()->user()->name }}
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
        @endauth
    </header>
    <main class="pg">
        @yield('content')
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
const sbToggle = document.getElementById('sidebarToggle');
const sb = document.getElementById('mainSidebar');
if(sbToggle && sb){
    sbToggle.addEventListener('click', ()=> sb.classList.toggle('open'));
    document.addEventListener('click', e=>{
        if(sb.classList.contains('open') && !sb.contains(e.target) && !sbToggle.contains(e.target))
            sb.classList.remove('open');
    });
}
toastr.options = {closeButton:true,progressBar:true,positionClass:'toast-top-right',timeOut:4000,escapeHtml:false};
@if(Session::has('success')) toastr.success("{!! Session::get('success') !!}"); @endif
@if(Session::has('warning')) toastr.warning("{!! Session::get('warning') !!}"); @endif
@if(Session::has('error')) toastr.error("{!! Session::get('error') !!}"); @endif
@if($errors->any()) @foreach($errors->all() as $error) toastr.error("{!! $error !!}"); @endforeach @endif
</script>
@yield('scripts')
</body>
</html>
