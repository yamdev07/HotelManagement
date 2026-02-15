@extends('template.auth')
@section('title', 'Login - Cactus Hotel')
@section('content')
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            display: flex;
            min-height: 600px;
        }

        /* Côté gauche avec l'icône et présentation */
        .login-left {
            background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
            color: white;
            padding: 3rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .login-left::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .hotel-icon {
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .hotel-icon i {
            font-size: 4rem;
            color: white;
            margin-bottom: 1rem;
            display: block;
        }

        .hotel-name {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        .hotel-slogan {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 3rem;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .features-list li {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .features-list i {
            background: rgba(255, 255, 255, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.1rem;
        }

        /* Côté droit avec le formulaire */
        .login-right {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-header h3 {
            color: #2E7D32;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #666;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #444;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #2E7D32;
            background: white;
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 2.7rem;
            color: #666;
            font-size: 1.1rem;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            margin-right: 0.5rem;
            width: 18px;
            height: 18px;
            border-color: #ddd;
        }

        .form-check-input:checked {
            background-color: #2E7D32;
            border-color: #2E7D32;
        }

        .form-check-label {
            color: #666;
            font-size: 0.9rem;
        }

        .forgot-link {
            color: #2E7D32;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #1B5E20;
        }

        .btn-login {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #1B5E20 0%, #2E7D32 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
        }

        .login-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #eee;
        }

        .login-divider span {
            background: white;
            padding: 0 1rem;
            color: #666;
            font-size: 0.85rem;
            position: relative;
            z-index: 1;
        }

        .demo-credentials {
            background: #f1f8e9;
            border: 1px solid #c8e6c9;
            border-radius: 10px;
            padding: 1.2rem;
            margin-top: 1.5rem;
        }

        .demo-credentials h6 {
            color: #2E7D32;
            margin-bottom: 0.8rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .demo-credentials p {
            margin: 0.3rem 0;
            color: #424242;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .demo-credentials strong {
            color: #2E7D32;
            min-width: 70px;
            display: inline-block;
        }

        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                max-width: 450px;
            }

            .login-left {
                padding: 2rem;
            }

            .login-right {
                padding: 2rem;
            }
        }
    </style>

    <div class="login-container">
        <div class="login-card">
            <!-- Côté gauche avec l'icône et présentation -->
            <div class="login-left">
                <div class="hotel-icon text-center">
                    <img src="{{ asset('img/logo_cactus1.jpeg') }}"
                        alt="Cactus Hotel"
                        class="mb-2"
                        style="height: 70px; width: auto;">

                    <div class="hotel-name">CACTUS HOTEL</div>
                    <div class="hotel-slogan">Luxury & Comfort in Every Stay</div>
                </div>


                <ul class="features-list">
                    <li>
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <strong>Sécurité garantie</strong>
                            <div style="font-size: 0.9rem; opacity: 0.8;">Vos données sont protégées</div>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-bolt"></i>
                        <div>
                            <strong>Accès rapide</strong>
                            <div style="font-size: 0.9rem; opacity: 0.8;">Gérez votre hôtel en un clic</div>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-headset"></i>
                        <div>
                            <strong>Support 24/7</strong>
                            <div style="font-size: 0.9rem; opacity: 0.8;">Assistance technique disponible</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Côté droit avec le formulaire -->
            <div class="login-right">
                <div class="login-header">
                    <h3>Welcome Back</h3>
                    <p>Please sign in to access your dashboard</p>
                </div>

                <form id="form-login" action="/login" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="position-relative">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Enter your email"
                                value="{{ old('email') }}" required autofocus>
                        </div>
                        @error('email')
                            <div class="text-danger mt-1" style="font-size: 0.85rem;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="position-relative">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter your password" required>
                        </div>
                        @error('password')
                            <div class="text-danger mt-1" style="font-size: 0.85rem;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="remember-forgot">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        <a href="/forgot-password" class="forgot-link">
                            Forgot Password?
                        </a>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i>
                        Sign In
                    </button>

                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-login');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            form.addEventListener('submit', function(e) {
                // Désactiver le bouton pendant la soumission
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...';
                
                // Réactiver après 3 secondes au cas où
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Sign In';
                }, 3000);
            });
        });
    </script>
@endsection