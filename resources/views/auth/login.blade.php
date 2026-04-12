<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name') }}</title>

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
            max-width: 420px;
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
            padding: 12px 20px;
            font-weight: 600;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5);
        }

        .btn-login {
            background-color: #fff;
            color: #764ba2;
            font-weight: 700;
            padding: 12px;
            border-radius: 50px;
            width: 100%;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            background-color: #f8f9fa;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-card mx-auto">
            <div class="text-center">
                <a href="{{ url('/') }}" style="text-decoration:none; color:#fff;">
                    <i class="fas fa-user-graduate icon-bg"></i>
                    <h2>Welcome Back</h2>
                </a>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0" style="border-radius: 50px 0 0 50px;">
                            <i class="fas fa-envelope text-muted"></i>
                        </span>
                        <input type="text" name="email" class="form-control" placeholder="Email or Roll Number" 
       style="border-radius: 0 50px 50px 0;" required autofocus>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0" style="border-radius: 50px 0 0 50px;">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                        <input type="password" name="password" class="form-control" placeholder="Password" 
                               style="border-radius: 0 50px 50px 0;" required>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="mb-3 form-check d-flex justify-content-between align-items-center">
                    <div>
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label" style="font-size: 0.9rem; color: #fff;">Remember Me</label>
                    </div>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="font-size: 0.9rem;">Forgot Password?</a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>
            </form>

            <div class="text-center mt-4 links">
                <p class="mb-0">Don't have an account? <a href="{{ route('register') }}">Create one</a></p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>