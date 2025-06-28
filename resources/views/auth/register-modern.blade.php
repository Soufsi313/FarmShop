<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - FarmShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
        }
        
        .auth-left {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .auth-right {
            padding: 60px 40px;
        }
        
        .brand-logo {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .brand-logo i {
            margin-right: 15px;
            color: #ffc107;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
        }
        
        .text-decoration-none {
            color: #28a745;
            font-weight: 500;
        }
        
        .text-decoration-none:hover {
            color: #1e7e34;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .form-floating label {
            color: #6c757d;
        }
        
        .auth-illustration {
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .auth-illustration i {
            font-size: 80px;
            color: white;
        }
        
        .password-strength {
            font-size: 12px;
            margin-top: 5px;
        }
        
        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #28a745; }
        
        @media (max-width: 768px) {
            .auth-left {
                display: none;
            }
            
            .auth-right {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="row g-0">
                <div class="col-lg-5">
                    <div class="auth-left">
                        <div class="auth-illustration">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h2>Rejoignez FarmShop !</h2>
                        <p class="lead">Créez votre compte pour découvrir les meilleurs produits locaux de votre région.</p>
                        <div class="mt-4">
                            <div class="mb-2">
                                <i class="fas fa-check-circle me-2"></i>
                                <span>Produits frais et locaux</span>
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-shipping-fast me-2"></i>
                                <span>Livraison rapide</span>
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-heart me-2"></i>
                                <span>Soutenez nos producteurs</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="auth-right">
                        <div class="text-center mb-4">
                            <div class="brand-logo">
                                <i class="fas fa-store"></i>
                                FarmShop
                            </div>
                            <h3>Créer un compte</h3>
                            <p class="text-muted">Rejoignez notre communauté de gourmets</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               placeholder="Nom complet"
                                               value="{{ old('name') }}" 
                                               required 
                                               autofocus>
                                        <label for="name">
                                            <i class="fas fa-user me-2"></i>Nom complet
                                        </label>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" 
                                               class="form-control @error('username') is-invalid @enderror" 
                                               id="username" 
                                               name="username" 
                                               placeholder="Nom d'utilisateur"
                                               value="{{ old('username') }}" 
                                               required>
                                        <label for="username">
                                            <i class="fas fa-at me-2"></i>Nom d'utilisateur
                                        </label>
                                        @error('username')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       placeholder="name@example.com"
                                       value="{{ old('email') }}" 
                                       required>
                                <label for="email">
                                    <i class="fas fa-envelope me-2"></i>Adresse email
                                </label>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Mot de passe"
                                               required>
                                        <label for="password">
                                            <i class="fas fa-lock me-2"></i>Mot de passe
                                        </label>
                                        <div id="passwordStrength" class="password-strength"></div>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="password" 
                                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Confirmer le mot de passe"
                                               required>
                                        <label for="password_confirmation">
                                            <i class="fas fa-lock me-2"></i>Confirmer le mot de passe
                                        </label>
                                        <div id="passwordMatch" class="password-strength"></div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="newsletter" id="newsletter" value="1" checked>
                                <label class="form-check-label" for="newsletter">
                                    <i class="fas fa-envelope-open-text me-2"></i>
                                    Je souhaite recevoir les actualités et offres spéciales par email
                                </label>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    J'accepte les <a href="#" class="text-decoration-none">conditions d'utilisation</a> 
                                    et la <a href="#" class="text-decoration-none">politique de confidentialité</a>
                                </label>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>Créer mon compte
                                </button>
                            </div>

                            <div class="text-center">
                                <p class="mb-0">Vous avez déjà un compte ?</p>
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                                    Se connecter
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Vérification de la force du mot de passe
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthDiv.innerHTML = '';
                return;
            }
            
            let strength = 0;
            let feedback = '';
            
            // Longueur
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Complexité
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            if (strength < 3) {
                strengthDiv.className = 'password-strength strength-weak';
                feedback = 'Mot de passe faible';
            } else if (strength < 5) {
                strengthDiv.className = 'password-strength strength-medium';
                feedback = 'Mot de passe moyen';
            } else {
                strengthDiv.className = 'password-strength strength-strong';
                feedback = 'Mot de passe fort';
            }
            
            strengthDiv.innerHTML = feedback;
        });
        
        // Vérification de la correspondance des mots de passe
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchDiv = document.getElementById('passwordMatch');
            
            if (confirmPassword.length === 0) {
                matchDiv.innerHTML = '';
                return;
            }
            
            if (password === confirmPassword) {
                matchDiv.className = 'password-strength strength-strong';
                matchDiv.innerHTML = 'Les mots de passe correspondent';
            } else {
                matchDiv.className = 'password-strength strength-weak';
                matchDiv.innerHTML = 'Les mots de passe ne correspondent pas';
            }
        }
        
        document.getElementById('password').addEventListener('input', checkPasswordMatch);
        document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);
    </script>
</body>
</html>
