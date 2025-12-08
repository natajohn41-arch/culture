@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #2c3e50;
        --success-color: #27ae60;
        --info-color: #17a2b8;
        --warning-color: #f39c12;
        --danger-color: #e74c3c;
        --light-bg: #f8f9fa;
        --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --hover-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }

    /* Cartes de statistiques */
    .stat-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        box-shadow: var(--card-shadow);
        background: white;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-card.border-info::before {
        background: linear-gradient(90deg, var(--info-color), #5bc0de);
    }

    .stat-card.border-success::before {
        background: linear-gradient(90deg, var(--success-color), #5cb85c);
    }

    .stat-card.border-primary::before {
        background: linear-gradient(90deg, var(--primary-color), #2980b9);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--hover-shadow);
    }

    .stat-card .card-body {
        padding: 2rem 1rem;
        position: relative;
        z-index: 1;
    }

    .stat-card i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .stat-card h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, var(--secondary-color), #34495e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Cartes principales */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        background: white;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background: linear-gradient(135deg, var(--secondary-color), #34495e);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        border: none;
    }

    .card-header h6 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
    }

    /* Boutons - CORRECTION ICI */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), #2980b9) !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 0.75rem 1.5rem !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3) !important;
        color: white !important;
    }

    .btn-primary:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 12px rgba(52, 152, 219, 0.4) !important;
        background: linear-gradient(135deg, #2980b9, var(--primary-color)) !important;
        color: white !important;
    }

    .btn-outline-primary {
        border: 2px solid var(--primary-color) !important;
        color: var(--primary-color) !important;
        border-radius: 8px !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
        background: transparent !important;
    }

    .btn-outline-primary:hover {
        background: var(--primary-color) !important;
        color: white !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3) !important;
    }

    .btn-sm {
        padding: 0.5rem 1rem !important;
        font-size: 0.875rem !important;
    }

    /* Liste des contenus */
    .border-bottom {
        border-bottom: 2px solid #f8f9fa !important;
        padding: 1.5rem 0;
        transition: all 0.3s ease;
    }

    .border-bottom:hover {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        transform: translateX(5px);
        border-bottom-color: var(--primary-color) !important;
        border-radius: 8px;
        padding: 1.5rem;
        margin: 0 -0.5rem;
    }

    .border-bottom:last-child {
        border-bottom: none !important;
    }

    .border-bottom h6 a {
        color: var(--secondary-color);
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .border-bottom h6 a:hover {
        color: var(--primary-color);
    }

    /* Badges */
    .badge {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.5em 0.75em;
        font-size: 0.75rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .bg-light {
        background: linear-gradient(135deg, #e9ecef, #dee2e6) !important;
        color: var(--secondary-color) !important;
    }

    /* Alertes */
    .alert-info {
        background: linear-gradient(135deg, rgba(23, 162, 184, 0.1), rgba(23, 162, 184, 0.05));
        border: 1px solid rgba(23, 162, 184, 0.2);
        border-radius: 10px;
        color: #0c5460;
        border-left: 4px solid var(--info-color);
    }

    /* Liste des statistiques */
    .list-unstyled li {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .list-unstyled li:hover {
        background: #f8f9fa;
        padding-left: 0.5rem;
        border-radius: 5px;
    }

    .list-unstyled li:last-child {
        border-bottom: none;
    }

    .list-unstyled li i {
        width: 20px;
        text-align: center;
    }

    /* Séparateurs */
    .border-top {
        border-color: #eaeaea !important;
    }

    /* Textes */
    .text-muted {
        color: #6c757d !important;
    }

    .small {
        font-size: 0.875rem;
    }

    /* Grille et espacements */
    .row {
        margin-bottom: 1rem;
    }

    .mb-4 {
        margin-bottom: 2rem !important;
    }

    .pt-3 {
        padding-top: 1.5rem !important;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stat-card, .card {
        animation: fadeInUp 0.6s ease-out;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }

    /* Responsive */
    @media (max-width: 768px) {
        .stat-card .card-body {
            padding: 1.5rem 1rem;
        }

        .stat-card i {
            font-size: 2.5rem;
        }

        .stat-card h3 {
            font-size: 2rem;
        }

        .card-header {
            padding: 1rem;
        }

        .border-bottom {
            padding: 1rem 0;
        }

        .border-bottom:hover {
            padding: 1rem;
        }

        .btn-group {
            flex-direction: column;
            gap: 0.5rem;
        }

        .btn-group .btn {
            width: 100%;
        }
    }

    /* Effets spéciaux */
    .d-grid.gap-2 {
        gap: 1rem !important;
    }

    .text-center .btn {
        margin-top: 0.5rem;
    }

    /* Couleurs spécifiques */
    .text-info { color: var(--info-color) !important; }
    .text-success { color: var(--success-color) !important; }
    .text-warning { color: var(--warning-color) !important; }
    .text-primary { color: var(--primary-color) !important; }

    .border-info { border-color: var(--info-color) !important; }
    .border-success { border-color: var(--success-color) !important; }
    .border-primary { border-color: var(--primary-color) !important; }
</style>

<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card stat-card border-info h-100">
            <div class="card-body text-center">
                <i class="bi bi-chat-text display-4 text-info mb-3"></i>
                <h3 class="fw-bold">{{ $mesCommentaires }}</h3>
                <p class="text-muted mb-0">Mes Commentaires</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card stat-card border-success h-100">
            <div class="card-body text-center">
                <i class="bi bi-heart display-4 text-success mb-3"></i>
                <h3 class="fw-bold">{{ $contenusFavoris }}</h3>
                <p class="text-muted mb-0">Favoris</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card stat-card border-primary h-100">
            <div class="card-body text-center">
                <i class="bi bi-eye display-4 text-primary mb-3"></i>
                <h3 class="fw-bold">{{ $derniersContenus->count() }}</h3>
                <p class="text-muted mb-0">Nouveaux Contenus</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Derniers contenus -->
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Derniers Contenus Culturels</h6>
            </div>
            <div class="card-body">
                @forelse($derniersContenus as $contenu)
                    <div class="border-bottom pb-3 mb-3">
                        <h6 class="mb-1">
                            <a href="{{ route('contenus.show', $contenu->id_contenu) }}" class="text-decoration-none">
                                {{ $contenu->titre }}
                            </a>
                        </h6>
                        <p class="text-muted small mb-2">{{ Str::limit(strip_tags($contenu->texte), 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-globe me-1"></i>{{ $contenu->region->nom_region }} •
                                <i class="bi bi-translate me-1 ms-2"></i>{{ $contenu->langue->nom_langue }} •
                                {{ $contenu->date_creation->format('d/m/Y') }}
                            </small>
                            <span class="badge bg-light text-dark">{{ $contenu->typeContenu->nom_contenu }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">Aucun contenu récent</p>
                @endforelse

                @if($derniersContenus->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('contenus.public') }}" class="btn btn-outline-primary btn-sm">
                            Voir tous les contenus
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Mon activité -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Mon Activité</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 mb-4">
                    <a href="{{ route('contenus.public') }}" class="btn btn-primary">
                        <i class="bi bi-compass me-2"></i>Explorer les Contenus
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                        <i class="bi bi-person me-2"></i>Mon Profil
                    </a>
                </div>

                <div class="border-top pt-3">
                    <h6 class="mb-3">Statistiques</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="bi bi-chat-text text-info me-2"></i>
                            <strong>{{ $mesCommentaires }}</strong> commentaires publiés
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-calendar text-success me-2"></i>
                            Membre depuis {{ auth()->user()->date_inscription->format('m/Y') }}
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-translate text-warning me-2"></i>
                            Langue: {{ auth()->user()->langue->nom_langue }}
                        </li>
                    </ul>
                </div>

                <div class="border-top pt-3">
                    <h6 class="mb-3">Conseils</h6>
                    <div class="alert alert-info small">
                        <i class="bi bi-lightbulb me-2"></i>
                        Partagez vos connaissances en commentant les contenus culturels !
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection