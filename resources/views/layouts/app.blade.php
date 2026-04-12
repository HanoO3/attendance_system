<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh;
            color: #fff;
        }
        .wrapper { display: flex; min-height: 100vh; }

        /* Sidebar Styling - MATCHING THEME */
        @auth
        .main-sidebar {
            width: 250px;
            /* UPDATED: Transparent Purple/White mix to match gradient */
            background: rgba(255, 255, 255, 0.05); 
            backdrop-filter: blur(15px); 
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1030;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        }
        .sidebar-brand {
            padding: 20px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 800;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .sidebar-menu { padding: 0; list-style: none; }
        .sidebar-menu li a {
            display: block;
            padding: 12px 20px;
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
            font-weight: 600;
            border-radius: 0 25px 25px 0;
            margin-right: 10px;
            margin-bottom: 5px;
        }
        .sidebar-menu li a:hover, .sidebar-menu li a.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .sidebar-menu li a i { margin-right: 10px; width: 20px; text-align: center; opacity: 0.8; }
        .sidebar-menu li a.active i { opacity: 1; }
        @endauth

        /* Content Wrapper */
        .content-wrapper {
            background-color: transparent; 
            @auth margin-left: 250px; width: calc(100% - 250px); @else margin-left: 0; width: 100%; @endauth
        }
        
        /* Top Navbar */
        .main-navbar {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 10px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .main-navbar .font-weight-bold { color: #fff; }
        .main-navbar .nav-link { color: #fff !important; font-weight: 600; }

        /* Card Styles */
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            color: #fff;
        }
        .card-header { background-color: transparent; border-bottom: 1px solid rgba(255,255,255,0.2); font-weight: bold; color: #fff; }
        .card-body { color: #fff; }
        
        /* Global Overrides */
        .text-gray-800 { color: #fff !important; }
        .text-primary { color: #fff !important; }
        .table { color: #fff; }
        .table > :not(caption) > * > * { background-color: transparent; color: #ffffff; border-color: rgba(255,255,255,0.1); }
        .custom-select-dark option { background-color: #ffffff !important; color: #000000 !important; padding: 10px; font-weight: 500; }
        a { color: #fff; }

        /* Responsive */
        @media (max-width: 768px) {
            @auth
            .main-sidebar { transform: translateX(-250px); }
            .content-wrapper { margin-left: 0; width: 100%; }
            .sidebar-open .main-sidebar { transform: translateX(0); }
            @endauth
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @auth
        <aside class="main-sidebar">
            <div class="sidebar-brand">
                <a href="{{ url('/') }}" style="color:white; text-decoration:none;">
                    <i class="fas fa-user-graduate"></i> <span>Attendance</span>
                </a>
            </div>
            <div class="sidebar">
                <ul class="sidebar-menu">
                    @if(in_array(auth()->user()->role, ['admin', 'teacher', 'super_admin']))
                        
                        <li>
                            <a href="{{ auth()->user()->role == 'teacher' ? route('teacher.dashboard') : route('dashboard') }}" 
                               class="{{ (request()->is('dashboard') || request()->is('teacher/dashboard')) ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>

                        <li><a href="{{ route('students.index') }}" class="{{ request()->is('students*') ? 'active' : '' }}"><i class="fas fa-users"></i> Students</a></li>

                        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'super_admin')
                            <li><a href="{{ route('teachers.index') }}" class="{{ request()->is('teachers*') ? 'active' : '' }}"><i class="fas fa-chalkboard-teacher"></i> Teachers</a></li>
                            <li><a href="{{ route('departments.index') }}" class="{{ request()->is('departments*') ? 'active' : '' }}"><i class="fas fa-building"></i> Departments</a></li>
                            <li><a href="{{ route('subjects.index') }}" class="{{ request()->is('subjects*') ? 'active' : '' }}"><i class="fas fa-book"></i> Subjects</a></li>
                        @endif

                        <li><a href="{{ route('attendance.index') }}" class="{{ request()->is('attendance*') ? 'active' : '' }}"><i class="fas fa-clipboard-check"></i> Attendance</a></li>
                        <li><a href="{{ route('reports.index') }}" class="{{ request()->is('reports*') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> Reports</a></li>
                        
                        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'super_admin')
                            <li><a href="{{ route('notifications.history') }}" class="{{ request()->is('notifications*') ? 'active' : '' }}"><i class="fas fa-bell"></i> Notifications</a></li>
                            <li>
                                <a href="{{ route('admin.requests') }}" class="{{ request()->is('profile-requests') ? 'active' : '' }}">
                                    <i class="fas fa-inbox"></i> Profile Requests
                                    @if(isset($pendingProfileCount) && $pendingProfileCount > 0)
                                        <span class="badge bg-danger float-end">{{ $pendingProfileCount }}</span>
                                    @endif
                                </a>
                            </li>
                            
                            <li>
                                <a href="{{ route('admin.leaves') }}" class="{{ request()->is('leave-requests') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-check"></i> Leave Approvals
                                    @if(isset($pendingLeavesCount) && $pendingLeavesCount > 0)
                                        <span class="badge bg-danger float-end">{{ $pendingLeavesCount }}</span>
                                    @endif
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->role == 'teacher')
                             <li>
                                <a href="{{ route('teacher.notifications') }}" class="{{ request()->is('teacher/notifications*') ? 'active' : '' }}">
                                    <i class="fas fa-bell"></i> Notifications
                                    @if(isset($teacherNotifBadge) && $teacherNotifBadge > 0)
                                        <span class="badge bg-danger float-end">{{ $teacherNotifBadge }}</span>
                                    @endif
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->role == 'super_admin')
                            <li>
                                <a href="{{ route('activity-logs.index') }}" class="{{ request()->is('activity-logs*') ? 'active' : '' }}">
                                    <i class="fas fa-history"></i> Activity Logs
                                    @if(isset($adminActivityBadge) && $adminActivityBadge > 0)
                                        <span class="badge bg-danger float-end">{{ $adminActivityBadge }}</span>
                                    @endif
                                </a>
                            </li>
                        @endif

                    @else
                        <!-- Student Menu -->
                        <li><a href="{{ route('student.dashboard') }}" class="{{ request()->is('my-dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> My Dashboard</a></li>
                        <li><a href="{{ route('student.attendance') }}" class="{{ request()->is('my-attendance') ? 'active' : '' }}"><i class="fas fa-clipboard-list"></i> My Attendance</a></li>
                        <li>
                            <a href="{{ route('student.leave') }}" class="{{ request()->is('my-leave') ? 'active' : '' }}">
                                <i class="fas fa-envelope-open-text"></i> Leave Request
                                @if(isset($studentLeaveBadge) && $studentLeaveBadge > 0)
                                    <span class="badge bg-danger float-end">{{ $studentLeaveBadge }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.notifications') }}" class="{{ request()->is('my-notifications') ? 'active' : '' }}">
                                <i class="fas fa-bell"></i> Notifications
                                @if(isset($studentNotifBadge) && $studentNotifBadge > 0)
                                    <span class="badge bg-danger float-end">{{ $studentNotifBadge }}</span>
                                @endif
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </aside>
        @endauth

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Top Navbar -->
            <nav class="main-navbar">
                <div>
                    <button class="btn btn-link d-md-none" id="sidebar-toggle" style="color: #fff;">
                        <i class="fas fa-bars"></i>
                    </button>
                    @auth
                        <span class="font-weight-bold">
                            {{ ucfirst(auth()->user()->role) }} Panel
                        </span>
                    @endauth
                </div>

                @auth
                <ul class="navbar-nav flex-row">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item text-dark" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                   <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
                @endauth
            </nav>

            <!-- Main Content -->
            <main class="content p-3">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    
    <!-- 1. jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- 2. Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- 3. Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- 4. Custom Script -->
    <script>
        // Sidebar Toggle
        document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
            document.body.classList.toggle('sidebar-open');
        });

        // Toastr Configuration
        toastr.options = { 
            "closeButton": true, 
            "progressBar": true, 
            "positionClass": "toast-top-right", 
            "timeOut": "5000",
            "escapeHtml": false
        };
        
        // Success Messages
        @if(Session::has('success'))
            toastr.success("{!! Session::get('success') !!}");
        @endif

        // Warning Messages
        @if(Session::has('warning'))
            toastr.warning("{!! Session::get('warning') !!}");
        @endif

        // Error Messages (General)
        @if(Session::has('error'))
            toastr.error("{!! Session::get('error') !!}");
        @endif

        // Validation Errors Loop
        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error("{!! $error !!}");
            @endforeach
        @endif
    </script>
</body>
</html>