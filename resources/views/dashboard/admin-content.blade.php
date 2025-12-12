<!-- Cartes de statistiques -->


@extends('layouts.app')

@section('title', 'Tableau de Bord Admin')

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

    /* Cartes de statistiques horizontales */
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

    .stat-card.border-info::before {
        background: linear-gradient(90deg, var(--info-color), #5bc0de);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--hover-shadow);
    }

    .stat-card .card-body {
        padding: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .stat-card i {
        font-size: 2.5rem;
        opacity: 0.9;
    }

    .stat-card h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, var(--secondary-color), #34495e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-card .card-title {
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    .btn-outline-success {
        border: 2px solid var(--success-color);
        color: var(--success-color);
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        background: transparent;
    }

    .btn-outline-success:hover {
        background: var(--success-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(39, 174, 96, 0.3);
    }

    .btn-outline-warning {
        border: 2px solid var(--warning-color);
        color: var(--warning-color);
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        background: transparent;
    }

    .btn-outline-warning:hover {
        background: var(--warning-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(243, 156, 18, 0.3);
    }

    .btn-outline-info {
        border: 2px solid var(--info-color);
        color: var(--info-color);
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        background: transparent;
    }

    .btn-outline-info:hover {
        background: var(--info-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    /* Listes */
    .list-group-item {
        border: none;
        border-bottom: 1px solid #f0f0f0;
        padding: 1rem 0;
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        transform: translateX(5px);
        border-radius: 8px;
        padding: 1rem;
        margin: 0 -0.5rem;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .px-0 {
        padding-left: 0 !important;
        padding-right: 0 !important;
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

    .bg-light {
        background: linear-gradient(135deg, #e9ecef, #dee2e6) !important;
        color: var(--secondary-color) !important;
    }

    /* Avatars utilisateurs */
    .rounded-circle.bg-primary {
        background: linear-gradient(135deg, var(--primary-color), #2980b9) !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Graphiques */
    .card-body canvas {
        border-radius: 8px;
    }

    /* Actions rapides */
    .row.g-3 [class*="col-"] {
        margin-bottom: 1rem;
    }

    .btn.w-100 {
        padding: 1rem 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .btn.w-100:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
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
    .stat-card:nth-child(4) { animation-delay: 0.4s; }

    /* Responsive */
    @media (max-width: 768px) {
        .stat-card .card-body {
            padding: 1.25rem;
        }

        .stat-card i {
            font-size: 2rem;
        }

        .stat-card h3 {
            font-size: 1.75rem;
        }

        .card-header {
            padding: 1rem;
        }

        .list-group-item {
            padding: 0.75rem 0;
        }

        .list-group-item:hover {
            padding: 0.75rem;
        }

        .btn.w-100 {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
        }

        .row.g-3 [class*="col-"] {
            margin-bottom: 0.75rem;
        }
    }

    /* Couleurs spécifiques */
    .text-primary { color: var(--primary-color) !important; }
    .text-success { color: var(--success-color) !important; }
    .text-warning { color: var(--warning-color) !important; }
    .text-info { color: var(--info-color) !important; }
    .text-danger { color: var(--danger-color) !important; }

    .border-primary { border-color: var(--primary-color) !important; }
    .border-success { border-color: var(--success-color) !important; }
    .border-warning { border-color: var(--warning-color) !important; }
    .border-info { border-color: var(--info-color) !important; }
</style>

<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-2">Total Contenus</h6>
                        <h3 class="fw-bold">{{ $totalContenus }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-file-text display-6 text-primary"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">
                        {{ $contenusEnAttente }} en attente de validation
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-2">Utilisateurs</h6>
                        <h3 class="fw-bold">{{ $totalUtilisateurs }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people display-6 text-success"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">
                        {{ $lastUtilisateurs->count() }} nouveaux ce mois
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-2">Commentaires</h6>
                        <h3 class="fw-bold">{{ $totalCommentaires }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-chat-text display-6 text-warning"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">
                        {{ $lastCommentaires->count() }} nouveaux aujourd'hui
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-2">Médias</h6>
                        <h3 class="fw-bold">{{ $totalMedias }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-images display-6 text-info"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">
                        {{ $totalTypeMedias }} types de médias
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Graphique Contenus par Statut -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Contenus par Statut</h6>
            </div>
            <div class="card-body">
                <canvas id="statutChart" width="400" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Graphique Contenus par Région -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Contenus par Région</h6>
            </div>
            <div class="card-body">
                <canvas id="regionChart" width="400" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Derniers Contenus -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Derniers Contenus</h6>
                <a href="{{ route('contenus.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @forelse($lastContenus as $contenu)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ Str::limit($contenu->titre, 40) }}</h6>
                                    <small class="text-muted">
                                        {{ $contenu->region->nom_region }} • 
                                        {{ $contenu->date_creation->format('d/m/Y') }}
                                    </small>
                                </div>
                                <span class="badge bg-{{ $contenu->statut == 'valide' ? 'success' : 'warning' }}">
                                    {{ $contenu->statut }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">Aucun contenu récent</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Derniers Utilisateurs -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Nouveaux Utilisateurs</h6>
                <a href="{{ route('utilisateurs.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @forelse($lastUtilisateurs as $user)
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                @if($user->hasPhoto())
                                    <img src="{{ $user->photo_url }}" 
                                         class="rounded-circle me-3" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3 text-white" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $user->prenom }} {{ $user->nom }}</h6>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                                <span class="badge bg-light text-dark ms-auto">
                                    {{ $user->role->nom_role }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">Aucun nouvel utilisateur</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions Rapides -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Actions Rapides</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <a href="{{ route('contenus.create') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>Nouveau Contenu
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('utilisateurs.create') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-person-plus me-2"></i>Nouvel Utilisateur
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('contenus.a-valider') }}" class="btn btn-outline-warning w-100">
                            <i class="bi bi-check-circle me-2"></i>Contenus à Valider
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('regions.index') }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-globe me-2"></i>Gérer Régions
                        </a>
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-12">
                        <a href="{{ route('admin.import.show') }}" class="btn btn-primary w-100">
                            <i class="bi bi-upload me-2"></i>📥 Importer les 230 Contenus Locaux
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des statuts
    const statutCtx = document.getElementById('statutChart').getContext('2d');
    const statutChart = new Chart(statutCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($contenusParStatut->keys()->toArray()) !!},
            datasets: [{
                data: {!! json_encode($contenusParStatut->values()->toArray()) !!},
                backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Graphique des régions
    const regionCtx = document.getElementById('regionChart').getContext('2d');
    const regionChart = new Chart(regionCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($contenusParRegion->keys()->toArray()) !!},
            datasets: [{
                label: 'Nombre de contenus',
                data: {!! json_encode($contenusParRegion->values()->toArray()) !!},
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush

@endsection