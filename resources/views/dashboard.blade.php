<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Culture Bénin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .stat-card {
            transition: transform 0.3s ease;
            border: none;
            border-left: 4px solid #007bff;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card.border-success { border-left-color: #28a745; }
        .stat-card.border-warning { border-left-color: #ffc107; }
        .stat-card.border-info { border-left-color: #17a2b8; }
        .stat-card.border-danger { border-left-color: #dc3545; }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation Dashboard -->
    <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i>Tableau de Bord
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ auth()->user()->prenom }} {{ auth()->user()->nom }}
                        <small class="badge bg-light text-dark ms-1">{{ auth()->user()->role->nom_role }}</small>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                        <li><a class="dropdown-item" href="{{ route('accueil') }}">Site Public</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Déconnexion</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <!-- En-tête selon le rôle -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">
                            @if(auth()->user()->isAdmin())
                                <i class="bi bi-shield-check text-primary me-2"></i>Administration
                            @elseif(auth()->user()->isModerator())
                                <i class="bi bi-eye-fill text-warning me-2"></i>Modération
                            @elseif(auth()->user()->isAuthor())
                                <i class="bi bi-pencil-fill text-success me-2"></i>Mes Contenus
                            @else
                                <i class="bi bi-person-fill text-info me-2"></i>Mon Tableau de Bord
                            @endif
                        </h1>
                        <p class="text-muted mb-0">
                            Bienvenue, {{ auth()->user()->prenom }} ! 
                            @if(auth()->user()->isAdmin())
                                Gestion complète de la plateforme
                            @elseif(auth()->user()->isModerator())
                                Validation des contenus et commentaires
                            @elseif(auth()->user()->isAuthor())
                                Création et gestion de vos contenus
                            @else
                                Consultation et interaction avec les contenus
                            @endif
                        </p>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">Dernière connexion: {{ auth()->user()->updated_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu selon le rôle -->
        @if(auth()->user()->isAdmin())
            @include('dashboard.admin-content')
        @elseif(auth()->user()->isModerator())
            @include('dashboard.moderator-content')
        @elseif(auth()->user()->isAuthor())
            @include('dashboard.author-content')
        @else
            @include('dashboard.user-content')
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>