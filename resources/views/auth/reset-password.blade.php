@extends('template.auth')

@section('content')
    <div class="auth-container">
        <!-- Animated Background -->
        <div class="auth-bg-animation">
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-4"></div>
                <div class="shape shape-5"></div>
            </div>
        </div>

        <!-- Glassmorphism Card -->
        <div class="auth-card">
            <div class="auth-card-body">
                <!-- Logo Section -->
                <div class="auth-logo-section">
                    <div class="auth-logo">
                        <i class="fas fa-key text-primary"></i>
                    </div>
                    <h1 class="auth-title">Nouveau mot de passe</h1>
                    <p class="auth-subtitle">Choisissez votre nouveau mot de passe</p>
                </div>

                <!-- Reset Form -->
                <form method="POST" action="{{ route('password.update') }}" class="auth-form" id="resetPasswordForm">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Adresse email
                        </label>
                        <div class="input-wrapper">
                            {{-- Issue #151 : champ éditable (il était readonly ET jamais pré-rempli) --}}
                            <input
                                id="email"
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email', $email ?? '') }}"
                                required
                                autocomplete="email"
                                autofocus
                                placeholder="Votre adresse email"
                            >
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Nouveau mot de passe
                        </label>
                        <div class="input-wrapper">
                            <input
                                id="password"
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Entrez votre nouveau mot de passe"
                                minlength="8"
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-toggle-icon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <!-- Password Strength Indicator -->
                        <div class="password-strength" id="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strength-fill"></div>
                            </div>
                            <div class="strength-text" id="strength-text">Force du mot de passe</div>
                        </div>
                        <!-- Checklist des conditions (issue #179), juste sous le champ -->
                        <div class="req-live" id="req-live">
                            <div class="requirement-item" id="req-length"><i class="fas fa-circle requirement-icon"></i><span>Au moins 8 caractères</span></div>
                            <div class="requirement-item" id="req-letter"><i class="fas fa-circle requirement-icon"></i><span>Au moins une lettre</span></div>
                            <div class="requirement-item" id="req-number"><i class="fas fa-circle requirement-icon"></i><span>Au moins un chiffre</span></div>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label for="password-confirm" class="form-label">
                            <i class="fas fa-shield-alt me-2"></i>Confirmer le mot de passe
                        </label>
                        <div class="input-wrapper">
                            <input
                                id="password-confirm"
                                type="password"
                                class="form-control"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Confirmez le mot de passe"
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('password-confirm')">
                                <i class="fas fa-eye" id="password-confirm-toggle-icon"></i>
                            </button>
                        </div>
                        <!-- Password Match Indicator -->
                        <div class="password-match" id="password-match" style="display: none;">
                            <i class="fas fa-check-circle me-1"></i>
                            <span>Les mots de passe correspondent</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary auth-submit-btn" id="submitBtn">
                        <span class="btn-text">
                            <i class="fas fa-key me-2"></i>
                            Réinitialiser le mot de passe
                        </span>
                        <span class="btn-loading d-none">
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            Réinitialisation...
                        </span>
                    </button>

                    <!-- Back to Login -->
                    <div class="auth-links">
                        <a href="{{ route('login') }}" class="auth-link">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour à la connexion
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
            width: 0%;
        }

        .strength-text {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .password-match {
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 600;
        }
        /* Issue #176 : couleurs nettes et lisibles (plus de vert/rouge mélangés illisibles) */
        .password-match.ok { color: var(--g400); }
        .password-match.ko { color: #f87171; }

        /* Issue #179 : checklist claire des conditions, sous le champ */
        .req-live {
            display: grid;
            gap: 0.4rem;
            margin-top: 0.7rem;
            padding: 0.7rem 0.9rem;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.6);
            transition: color 0.25s ease;
        }

        .requirement-item.met {
            color: var(--g400);
        }

        .requirement-icon {
            font-size: 0.7rem;
            width: 14px;
            text-align: center;
            transition: all 0.25s ease;
        }
        /* Puce grise -> coche verte quand la condition est remplie */
        .requirement-item .requirement-icon::before { content: "\f111"; } /* fa-circle */
        .requirement-item.met .requirement-icon { color: var(--g400); }
        .requirement-item.met .requirement-icon::before { content: "\f058"; } /* fa-check-circle */

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        .input-wrapper {
            position: relative;
        }

        .form-control[readonly] {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }
    </style>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + '-toggle-icon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.className = 'fas fa-eye-slash';
            } else {
                passwordField.type = 'password';
                toggleIcon.className = 'fas fa-eye';
            }
        }

        // Conditions RÉELLES appliquées côté serveur (App\Rules\StrongPassword)
        function checkPasswordStrength(password) {
            let score = 0;
            const requirements = {
                length: password.length >= 8,
                letter: /\p{L}/u.test(password),
                number: /\d/.test(password)
            };

            Object.keys(requirements).forEach(req => {
                const element = document.getElementById(`req-${req}`);
                if (!element) return;
                if (requirements[req]) {
                    element.classList.add('met');
                    score++;
                } else {
                    element.classList.remove('met');
                }
            });

            return { score, total: 3 };
        }

        // Update password strength indicator
        function updatePasswordStrength(password) {
            const { score, total } = checkPasswordStrength(password);
            const strengthFill = document.getElementById('strength-fill');
            const strengthText = document.getElementById('strength-text');

            const percentage = (score / total) * 100;
            strengthFill.style.width = percentage + '%';

            if (score === 0) {
                strengthFill.style.background = 'transparent';
                strengthText.textContent = 'Force du mot de passe';
                strengthText.style.color = 'rgba(255, 255, 255, 0.7)';
            } else if (score === 1) {
                strengthFill.style.background = '#f87171';
                strengthText.textContent = 'Mot de passe faible';
                strengthText.style.color = '#f87171';
            } else if (score === 2) {
                strengthFill.style.background = '#fbbf24';
                strengthText.textContent = 'Mot de passe moyen';
                strengthText.style.color = '#fbbf24';
            } else {
                strengthFill.style.background = 'var(--g400)';
                strengthText.textContent = 'Mot de passe valide';
                strengthText.style.color = 'var(--g400)';
            }
        }

        // Check if passwords match
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password-confirm').value;
            const matchIndicator = document.getElementById('password-match');

            if (confirmPassword.length > 0) {
                matchIndicator.style.display = 'block';
                if (password === confirmPassword) {
                    matchIndicator.className = 'password-match ok';
                    matchIndicator.innerHTML = '<i class="fas fa-check-circle me-1"></i><span>Les mots de passe correspondent</span>';
                } else {
                    matchIndicator.className = 'password-match ko';
                    matchIndicator.innerHTML = '<i class="fas fa-times-circle me-1"></i><span>Les mots de passe ne correspondent pas</span>';
                }
            } else {
                matchIndicator.style.display = 'none';
            }
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', function() {
            updatePasswordStrength(this.value);
            checkPasswordMatch();
        });

        document.getElementById('password-confirm').addEventListener('input', checkPasswordMatch);

        // Form submission
        document.getElementById('resetPasswordForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Show loading state
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-loading-active');
        });

        // Add smooth focus animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('input-focused');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('input-focused');
            });
        });
    </script>
@endsection
