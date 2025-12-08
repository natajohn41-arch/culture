<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Culture Bénin')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 0.75rem 1rem;
        }
        .sidebar .nav-link:hover {
            background: #34495e;
            color: #3498db;
        }
        .sidebar .nav-link.active {
            background: #3498db;
            color: white;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .stat-card {
            border-left: 4px solid #007bff;
        }
        .stat-card.success { border-left-color: #28a745; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-card.danger { border-left-color: #dc3545; }
    </style>
</head>
<body>
    <!-- Navigation Top -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-globe-europe-africa me-2"></i>
                Culture Bénin
                @auth
                    <small class="badge bg-light text-dark ms-2">{{ auth()->user()->role->nom_role }}</small>
                @endauth
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    
                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->isModerator())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('contenus.index') }}">
                                    <i class="bi bi-file-text me-1"></i>Contenus
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isAuthor())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mes.contenus.index') }}">
                                    <i class="bi bi-file-text me-1"></i>Mes Contenus
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear me-1"></i>Administration
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('utilisateurs.index') }}">Utilisateurs</a></li>
                                    <li><a class="dropdown-item" href="{{ route('regions.index') }}">Régions</a></li>
                                    <li><a class="dropdown-item" href="{{ route('langues.index') }}">Langues</a></li>
                                    <li><a class="dropdown-item" href="{{ route('roles.index') }}">Rôles</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('type-contenus.index') }}">Types de Contenu</a></li>
                                    <li><a class="dropdown-item" href="{{ route('type-medias.index') }}">Types de Média</a></li>
                                </ul>
                            </li>
                        @endif

                        @if(auth()->user()->isModerator() || auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('contenus.a-valider') }}">
                                    <i class="bi bi-check-circle me-1"></i>À Valider
                                    @php
                                        $contenusEnAttente = \App\Models\Contenu::where('statut', 'en_attente')->count();
                                    @endphp
                                    @if($contenusEnAttente > 0)
                                        <span class="badge bg-warning ms-1">{{ $contenusEnAttente }}</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                @if(auth()->user()->photo)
                                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 32px; height: 32px; object-fit: cover;">
                                @else
                                    <i class="bi bi-person-circle me-2"></i>
                                @endif
                                {{ auth()->user()->prenom }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person me-2"></i>Mon Profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('accueil') }}">
                                        <i class="bi bi-house me-2"></i>Site Public
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Inscription</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu Principal -->
    <div class="container-fluid py-4">
        <!-- Messages de session -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Contenu de la page -->
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>