<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Attendance System') }}</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); /* Modern Gradient Background */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #fff;
        }

        /* Navbar */
        .navbar-custom {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: #fff !important;
        }
        .nav-link {
            color: #fff !important;
            font-weight: 600;
        }

        /* Hero Section */
        .hero-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        /* Buttons */
        .btn-custom-light {
            background-color: #fff;
            color: #764ba2;
            font-weight: 700;
            padding: 12px 35px;
            border-radius: 50px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            margin: 10px;
        }
        .btn-custom-light:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            color: #764ba2;
        }
        
        .btn-custom-outline {
            background-color: transparent;
            color: #fff;
            border: 2px solid #fff;
            font-weight: 700;
            padding: 12px 35px;
            border-radius: 50px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            margin: 10px;
        }
        .btn-custom-outline:hover {
            background-color: #fff;
            color: #764ba2;
            transform: translateY(-3px);
        }

        /* Features Cards */
        .features {
            margin-top: 60px;
        }
        .feature-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 15px;
            transition: 0.3s;
        }
        .feature-card:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-5px);
        }
        .feature-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .feature-card h5 {
            font-weight: 700;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background-color: rgba(0,0,0,0.1);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .hero-content h1 { font-size: 2.5rem; }
            .btn-custom-light, .btn-custom-outline { display: block; width: 100%; margin: 10px 0; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-user-graduate"></i> Attendance
            </a>
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="btn btn-custom-outline btn-sm me-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-custom-light btn-sm">Register</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1>Students Attendance Tracking System</h1>
                <p>Manage your students, track attendance efficiently, and generate detailed reports with just a few clicks.</p>
                
                <div class="d-flex justify-content-center align-items-center flex-wrap">
                    <a href="{{ route('login') }}" class="btn btn-custom-light">
                        <i class="fas fa-sign-in-alt me-2"></i> Login Now
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-custom-outline">
                        <i class="fas fa-user-plus me-2"></i> Create Account
                    </a>
                </div>

                <!-- Features Row -->
                <div class="row features justify-content-center mt-5">
                    <div class="col-md-4 mb-4">
                        <div class="feature-card">
                            <i class="fas fa-clipboard-check"></i>
                            <h5>Easy Attendance</h5>
                            <p class="small mb-0">Mark attendance quickly using our intuitive interface.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="feature-card">
                            <i class="fas fa-chart-pie"></i>
                            <h5>Detailed Reports</h5>
                            <p class="small mb-0">Generate PDF reports for students and departments.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="feature-card">
                            <i class="fas fa-users-cog"></i>
                            <h5>User Management</h5>
                            <p class="small mb-0">Manage Students, Teachers, and Admins seamlessly.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; {{ date('Y') }} Attendance System. All Rights Reserved.
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>