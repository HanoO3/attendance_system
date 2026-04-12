<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - {{ config('app.name') }}</title>

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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .auth-card {
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .auth-card h2 {
            font-weight: 800;
            margin-bottom: 30px;
            text-align: center;
            color: #fff;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5);
        }

        .btn-register {
            background-color: #fff;
            color: #764ba2;
            font-weight: 700;
            padding: 12px;
            border-radius: 50px;
            width: 100%;
            transition: all 0.3s ease;
            border: none;
            margin-top: 10px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .links a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }
        
        .links a:hover {
            text-decoration: underline;
        }

        .icon-bg {
            font-size: 3rem;
            margin-bottom: 20px;
            display: block;
            text-align: center;
        }
        
        /* Error Styling */
        .alert-danger {
            background-color: rgba(255, 255, 255, 0.9);
            color: #dc3545;
            border-radius: 10px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-card mx-auto">
            <div class="text-center">
                <a href="{{ url('/') }}" style="text-decoration:none; color:#fff;">
                    <i class="fas fa-user-plus icon-bg"></i>
                    <h2>Create Account</h2>
                </a>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0" style="border-radius: 50px 0 0 50px;">
                            <i class="fas fa-user text-muted"></i>
                        </span>
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Full Name" 
                               style="border-radius: 0 50px 50px 0;" value="{{ old('name') }}" required>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0" style="border-radius: 50px 0 0 50px;">
                            <i class="fas fa-envelope text-muted"></i>
                        </span>
                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Email Address" 
                               style="border-radius: 0 50px 50px 0;" value="{{ old('email') }}" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0" style="border-radius: 50px 0 0 50px;">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                        <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Password" 
                               style="border-radius: 0 50px 50px 0;" required>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0" style="border-radius: 50px 0 0 50px;">
                            <i class="fas fa-key text-muted"></i>
                        </span>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" 
                               style="border-radius: 0 50px 50px 0;" required>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-register">
                    <i class="fas fa-rocket me-2"></i> Register
                </button>
            </form>

            <div class="text-center mt-4 links">
                <p class="mb-0">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>