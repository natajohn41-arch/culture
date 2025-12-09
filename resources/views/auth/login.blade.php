<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Connexion - Culture Bénin</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-icon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-icon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
    </style>
</head>
<body>
    <div class="login-container d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card login-card">
                        <div class="card-body p-5">
                            <!-- Logo et titre -->
                            <div class="text-center mb-5">
                                <i class="bi bi-globe-europe-africa display-4 text-primary mb-3"></i>
                                <h2 class="fw-bold">Culture Bénin</h2>
                                <p class="text-muted">Connectez-vous à votre compte</p>
                            </div>

                            <!-- Messages d'erreur -->
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle me-2"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Formulaire de connexion -->
                            <form action="{{ route('login.post') }}" method="POST" autocomplete="on">
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label">Adresse email</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="votre@email.com" 
                                               autocomplete="email"
                                               required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Votre mot de passe" 
                                               autocomplete="current-password"
                                               required>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    Se connecter
                                </button>

                                <div class="text-center">
                                    <p class="mb-0">
                                        Pas encore de compte ? 
                                        <a href="{{ route('register') }}" class="text-decoration-none">Créer un compte</a>
                                    </p>
                                </div>
                            </form>

                            <hr class="my-4">

                            <div class="text-center">
                                <a href="{{ route('accueil') }}" class="text-muted text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Retour au site public
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>