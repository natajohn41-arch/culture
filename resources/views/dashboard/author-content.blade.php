
@extends('layouts.app')

@section('title', 'Tableau de Bord Auteur')

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

    .stat-card.border-primary::before {
        background: linear-gradient(90deg, var(--primary-color), #2980b9);
    }

    .stat-card.border-success::before {
        background: linear-gradient(90deg, var(--success-color), #5cb85c);
    }

    .stat-card.border-warning::before {
        background: linear-gradient(90deg, var(--warning-color), #f0ad4e);
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

    .card-header.d-flex {
        background: linear-gradient(135deg, var(--primary-color), #2980b9) !important;
    }

    .card-header h6 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
    }

    /* Boutons */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), #2980b9);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(52, 152, 219, 0.4);
        background: linear-gradient(135deg, #2980b9, var(--primary-color));
    }

    .btn-outline-primary {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        background: transparent;
    }

    .btn-outline-primary:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
    }

    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        background: transparent;
    }

    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
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

    /* Badges */
    .badge {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.5em 0.75em;
        font-size: 0.75rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .bg-success {
        background: linear-gradient(135deg, var(--success-color), #229954) !important;
    }

    .bg-warning {
        background: linear-gradient(135deg, var(--warning-color), #e67e22) !important;
    }

    .bg-danger {
        background: linear-gradient(135deg, var(--danger-color), #c0392b) !important;
    }

    /* Bouton d'édition */
    .btn-outline-secondary.btn-sm {
        border: 1px solid #6c757d;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary.btn-sm:hover {
        background: #6c757d;
        transform: scale(1.05);
    }

    /* Section statistiques personnelles */
    .border.rounded {
        border: 1px solid #e9ecef !important;
        border-radius: 8px !important;
        transition: all 0.3s ease;
    }

    .border.rounded:hover {
        border-color: var(--primary-color) !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    /* Section vide */
    .text-center.py-4 {
        padding: 3rem 1rem !important;
    }

    .text-center.py-4 i {
        font-size: 4rem;
        opacity: 0.5;
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

    .mt-4 {
        margin-top: 2rem !important;
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

        .d-grid.gap-2 {
            gap: 0.75rem !important;
        }

        .text-center.py-4 i {
            font-size: 3rem;
        }
    }

    /* Effets spéciaux */
    .d-grid.gap-2 {
        gap: 1rem !important;
    }

    /* Couleurs spécifiques */
    .text-primary { color: var(--primary-color) !important; }
    .text-success { color: var(--success-color) !important; }
    .text-warning { color: var(--warning-color) !important; }
    .text-danger { color: var(--danger-color) !important; }
    .text-muted { color: #6c757d !important; }

    .border-primary { border-color: var(--primary-color) !important; }
    .border-success { border-color: var(--success-color) !important; }
    .border-warning { border-color: var(--warning-color) !important; }
</style>


<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card stat-card border-primary h-100">
            <div class="card-body text-center">
                <i class="bi bi-file-text display-4 text-primary mb-3"></i>
                <h3 class="fw-bold">{{ $mesContenus }}</h3>
                <p class="text-muted mb-0">Mes Contenus</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card stat-card border-success h-100">
            <div class="card-body text-center">
                <i class="bi bi-check-circle display-4 text-success mb-3"></i>
                <h3 class="fw-bold">{{ $contenusValides }}</h3>
                <p class="text-muted mb-0">Contenus Validés</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card stat-card border-warning h-100">
            <div class="card-body text-center">
                <i class="bi bi-clock-history display-4 text-warning mb-3"></i>
                <h3 class="fw-bold">{{ $contenusEnAttente }}</h3>
                <p class="text-muted mb-0">En Attente</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Mes derniers contenus -->
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Mes Derniers Contenus</h6>
                <a href="{{ route('mes.contenus.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body">
                @forelse($mesDerniersContenus as $contenu)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0">{{ $contenu->titre }}</h6>
                            <span class="badge bg-{{ $contenu->statut == 'valide' ? 'success' : ($contenu->statut == 'en_attente' ? 'warning' : 'danger') }}">
                                {{ $contenu->statut }}
                            </span>
                        </div>
                        <p class="text-muted small mb-2">{{ Str::limit(strip_tags($contenu->texte), 120) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-globe me-1"></i>{{ $contenu->region->nom_region }} • 
                                <i class="bi bi-translate me-1 ms-2"></i>{{ $contenu->langue->nom_langue }} •
                                {{ $contenu->date_creation->format('d/m/Y') }}
                            </small>
                            <div>
                                <a href="{{ route('mes.contenus.edit', $contenu->id_contenu) }}" 
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-file-text display-4 text-muted mb-3"></i>
                        <p class="text-muted">Vous n'avez pas encore créé de contenu.</p>
                        <a href="{{ route('mes.contenus.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Créer mon premier contenu
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Actions Rapides</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('mes.contenus.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Nouveau Contenu
                    </a>
                    <a href="{{ route('mes.contenus.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-list-ul me-2"></i>Mes Contenus
                    </a>
                    <a href="{{ route('contenus.public') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-eye me-2"></i>Voir les Contenus Publics
                    </a>
                </div>

                <!-- Statistiques personnelles -->
                <div class="mt-4 pt-3 border-top">
                    <h6 class="mb-3">Mes Statistiques</h6>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-2 mb-2">
                                <small class="text-muted d-block">Validés</small>
                                <strong class="text-success">{{ $contenusValides }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 mb-2">
                                <small class="text-muted d-block">En attente</small>
                                <strong class="text-warning">{{ $contenusEnAttente }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection