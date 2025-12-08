<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Culture CMS</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .hero-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; color: white; }
        .feature-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; }
        .btn-culture { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white; padding: 12px 30px; border-radius: 25px; font-weight: 600; }
        .btn-culture:hover { color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
        .card-auth { border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
        .nav-link { color: #333 !important; font-weight: 500; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-globe-europe-africa me-2 text-primary"></i>
                Culture
            </a>
            <div class="d-flex">
                <a href="{{ route('register') }}" class="btn btn-outline-primary me-2">Inscription</a>
                <a href="{{ url('/') }}" class="btn btn-outline-secondary">Accueil</a>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card card-auth p-4 bg-white text-dark">
                        <div class="text-center mb-3">
                            <div class="feature-icon mx-auto">
                                <i class="bi bi-person-circle text-white fs-3"></i>
                            </div>
                            <h3 class="mt-2">Connexion</h3>
                            <p class="text-muted">Connectez-vous pour accéder à votre tableau de bord</p>
                        </div>

                        @if(session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse e-mail</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Se souvenir de moi</label>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('password.request') ?? '#' }}" class="text-decoration-none">Mot de passe oublié ?</a>
                                <button type="submit" class="btn btn-culture">Se connecter</button>
                            </div>
                        </form>

                        <div class="mt-3 text-center">
                            <small>Pas de compte ? <a href="{{ route('register') }}">Inscrivez-vous</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
