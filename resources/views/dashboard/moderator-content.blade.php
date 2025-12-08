@extends('layouts.app')

@section('title', 'Tableau de Bord Modération')

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

    .stat-card.border-warning::before {
        background: linear-gradient(90deg, var(--warning-color), #f0ad4e);
    }

    .stat-card.border-success::before {
        background: linear-gradient(90deg, var(--success-color), #5cb85c);
    }

    .stat-card.border-info::before {
        background: linear-gradient(90deg, var(--info-color), #5bc0de);
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

    .card-header.bg-warning {
        background: linear-gradient(135deg, var(--warning-color), #e67e22) !important;
    }

    .card-header h6 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
    }

    /* Boutons */
    .btn-success {
        background: linear-gradient(135deg, var(--success-color), #229954);
        border: none;
        border-radius: 6px;
        padding: 0.375rem 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(39, 174, 96, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(39, 174, 96, 0.4);
        background: linear-gradient(135deg, #229954, var(--success-color));
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color), #c0392b);
        border: none;
        border-radius: 6px;
        padding: 0.375rem 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(231, 76, 60, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(231, 76, 60, 0.4);
        background: linear-gradient(135deg, #c0392b, var(--danger-color));
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--warning-color), #e67e22);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(243, 156, 18, 0.3);
        color: white;
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(243, 156, 18, 0.4);
        background: linear-gradient(135deg, #e67e22, var(--warning-color));
        color: white;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    /* Liste des contenus et commentaires */
    .border-bottom {
        border-bottom: 2px solid #f8f9fa !important;
        padding: 1.25rem 0;
        transition: all 0.3s ease;
    }

    .border-bottom:hover {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        transform: translateX(5px);
        border-bottom-color: var(--primary-color) !important;
        border-radius: 8px;
        padding: 1.25rem;
        margin: 0 -0.5rem;
    }

    .border-bottom:last-child {
        border-bottom: none !important;
    }

    /* Style spécifique pour les contenus à valider */
    .card-header.bg-warning + .card-body .border-bottom:hover {
        border-bottom-color: var(--warning-color) !important;
    }

    /* Badges et textes */
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

    /* Effets spéciaux pour les boutons d'action */
    .btn-group-sm .btn {
        margin: 0 2px;
    }

    /* Couleurs spécifiques */
    .text-info { color: var(--info-color) !important; }
    .text-success { color: var(--success-color) !important; }
    .text-warning { color: var(--warning-color) !important; }
    .text-danger { color: var(--danger-color) !important; }

    .border-warning { border-color: var(--warning-color) !important; }
    .border-success { border-color: var(--success-color) !important; }
    .border-info { border-color: var(--info-color) !important; }

    /* Style pour les en-têtes de carte spécifiques */
    .card-header.bg-warning {
        background: linear-gradient(135deg, var(--warning-color), #e67e22) !important;
    }
</style>

<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card stat-card border-warning h-100">
            <div class="card-body text-center">
                <i class="bi bi-clock-history display-4 text-warning mb-3"></i>
                <h3 class="fw-bold">{{ $contenusEnAttente }}</h3>
                <p class="text-muted mb-0">Contenus en attente</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card stat-card border-success h-100">
            <div class="card-body text-center">
                <i class="bi bi-check-circle display-4 text-success mb-3"></i>
                <h3 class="fw-bold">{{ $contenusValides }}</h3>
                <p class="text-muted mb-0">Contenus validés</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card stat-card border-info h-100">
            <div class="card-body text-center">
                <i class="bi bi-chat-text display-4 text-info mb-3"></i>
                <h3 class="fw-bold">{{ $totalCommentaires }}</h3>
                <p class="text-muted mb-0">Commentaires totaux</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Contenus à valider -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-warning text-white">
                <h6 class="mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Contenus à Valider ({{ $contenusAValider->count() }})
                </h6>
            </div>
            <div class="card-body">
                @forelse($contenusAValider as $contenu)
                    <div class="border-bottom pb-3 mb-3">
                        <h6 class="mb-1">{{ $contenu->titre }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit(strip_tags($contenu->texte), 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Par {{ $contenu->auteur->prenom }} • 
                                {{ $contenu->date_creation->format('d/m/Y') }}
                            </small>
                            <div>
                                <form action="{{ route('contenus.valider', $contenu->id_contenu) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Valider ce contenu ?')">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('contenus.rejeter', $contenu->id_contenu) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Rejeter ce contenu ?')">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">Aucun contenu à valider</p>
                @endforelse
                
                @if($contenusAValider->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('contenus.a-valider') }}" class="btn btn-warning btn-sm">
                            Voir tous les contenus à valider
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Derniers commentaires -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Derniers Commentaires</h6>
            </div>
            <div class="card-body">
                @forelse($derniersCommentaires as $commentaire)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <strong>{{ $commentaire->utilisateur->prenom }}</strong>
                            <small class="text-muted">{{ $commentaire->date->format('d/m H:i') }}</small>
                        </div>
                        <p class="mb-2 small">{{ Str::limit($commentaire->texte, 80) }}</p>
                        <small class="text-muted">
                            Sur: {{ Str::limit($commentaire->contenu->titre, 30) }}
                        </small>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">Aucun commentaire récent</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection