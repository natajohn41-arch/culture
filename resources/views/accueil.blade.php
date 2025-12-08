@extends('layouts.public')

@section('title', 'Accueil - Culture Bénin')

@push('styles')
<style>
    /* Hero Section avec vidéo/image en arrière-plan */
    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        object-fit: cover;
    }
    
    .hero-background-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%),
                    url('https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
    }
    
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.4) 100%);
        z-index: 2;
    }
    
    .hero-content {
        position: relative;
        z-index: 3;
        color: white;
        text-align: center;
        padding: 2rem;
    }
    
    .hero-title {
        font-size: 4rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        animation: fadeInUp 1s ease-out;
    }
    
    .hero-subtitle {
        font-size: 1.5rem;
        margin-bottom: 2rem;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        animation: fadeInUp 1.2s ease-out;
    }
    
    .hero-cta {
        animation: fadeInUp 1.4s ease-out;
    }
    
    .btn-hero {
        padding: 1rem 2.5rem;
        font-size: 1.2rem;
        font-weight: 600;
        border-radius: 50px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }
    
    .btn-hero:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Statistiques améliorées */
    .stats-section {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 5rem 0;
        position: relative;
        overflow: hidden;
    }
    
    .stats-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        border: none;
    }
    
    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .stat-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        margin: 1rem 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Section contenus */
    .content-section {
        padding: 5rem 0;
        background: white;
    }
    
    .section-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .section-subtitle {
        font-size: 1.2rem;
        color: #6c757d;
        margin-bottom: 3rem;
    }
    
    /* Régions section */
    .regions-section {
        padding: 5rem 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }
    
    #regions-scroll .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    #regions-scroll .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15) !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .section-title {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section avec vidéo/image en arrière-plan -->
<section class="hero-section">
    <!-- Image de fond avec overlay -->
    <div class="hero-background-image"></div>
    
    <!-- Overlay pour améliorer la lisibilité -->
    <div class="hero-overlay"></div>
    
    <!-- Contenu principal -->
    <div class="hero-content">
        <div class="container">
            <h1 class="hero-title">Découvrez la Culture Béninoise</h1>
            <p class="hero-subtitle">Explorez la richesse culturelle, linguistique et historique des régions du Bénin</p>
            <div class="hero-cta">
                <a href="{{ route('contenus.public') }}" class="btn btn-light btn-hero me-3">
                    <i class="bi bi-compass me-2"></i>Explorer les contenus
                </a>
                <a href="{{ route('about') }}" class="btn btn-outline-light btn-hero">
                    <i class="bi bi-info-circle me-2"></i>En savoir plus
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Statistiques -->
<section class="stats-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="stat-card">
                    <i class="bi bi-globe-americas stat-icon"></i>
                    <div class="stat-number">{{ $totalRegions ?? 0 }}</div>
                    <p class="text-muted mb-0">Régions</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stat-card">
                    <i class="bi bi-translate stat-icon"></i>
                    <div class="stat-number">{{ $totalLangues ?? 0 }}</div>
                    <p class="text-muted mb-0">Langues</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stat-card">
                    <i class="bi bi-file-text stat-icon"></i>
                    <div class="stat-number">{{ $totalContenus ?? 0 }}</div>
                    <p class="text-muted mb-0">Contenus</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stat-card">
                    <i class="bi bi-people stat-icon"></i>
                    <div class="stat-number">{{ $totalUtilisateurs ?? 0 }}</div>
                    <p class="text-muted mb-0">Membres</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contenus Récents -->
<section class="content-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title">Contenus Récents</h2>
                <p class="section-subtitle">Découvrez les derniers contenus ajoutés à notre plateforme</p>
            </div>
        </div>

        <div id="contenus-scroll" class="d-flex flex-row overflow-x-auto pb-3" style="scroll-behavior: smooth;">
            @forelse($contenusRecents as $contenu)
                <div class="card culture-card me-3" style="min-width: 300px; flex-shrink: 0;">
                    @if($contenu->medias && $contenu->medias->count() > 0)
                        @php 
                            $firstMedia = $contenu->medias->first();
                            // Détecter le type MIME si non défini
                            $mimeType = $firstMedia->mime_type ?? $firstMedia->getMimeTypeOuDetecte();
                            $mediaPath = asset('storage/' . $firstMedia->chemin);
                        @endphp
                        
                        @if(str_starts_with($mimeType, 'image/'))
                            <img src="{{ $mediaPath }}"
                                 class="card-img-top" alt="{{ $contenu->titre }}" 
                                 style="height: 200px; object-fit: cover;"
                                 onerror="this.src='https://via.placeholder.com/300x200?text=Image+non+disponible'">
                        @elseif(str_starts_with($mimeType, 'video/'))
                            <div class="card-img-top position-relative bg-dark" style="height: 200px; overflow: hidden;">
                                <video src="{{ $mediaPath }}" 
                                       class="w-100 h-100" 
                                       style="object-fit: cover;"
                                       controls
                                       preload="metadata"
                                       onerror="this.parentElement.innerHTML='<div class=\'d-flex align-items-center justify-content-center h-100 text-white\'><i class=\'bi bi-exclamation-triangle me-2\'></i>Vidéo non disponible</div>'">
                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                </video>
                            </div>
                        @elseif(str_starts_with($mimeType, 'audio/'))
                            <div class="card-img-top bg-light d-flex flex-column align-items-center justify-content-center p-3" style="height: 200px;">
                                <i class="bi bi-music-note-beamed text-primary mb-2" style="font-size: 2.5rem;"></i>
                                <audio src="{{ $mediaPath }}" controls style="width: 100%;"></audio>
                            </div>
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-file-earmark text-muted" style="font-size: 3rem;"></i>
                                <div class="ms-2">
                                    <small class="text-muted d-block">{{ $firstMedia->description ?? 'Document' }}</small>
                                    <a href="{{ $mediaPath }}" target="_blank" class="btn btn-sm btn-primary mt-2">
                                        <i class="bi bi-download"></i> Télécharger
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge region-badge">{{ $contenu->region->nom_region }}</span>
                            <span class="badge bg-info">{{ $contenu->langue->nom_langue }}</span>
                            @if($contenu->est_premium)
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-star-fill me-1"></i>PREMIUM
                                </span>
                            @endif
                        </div>

                        <h5 class="card-title">{{ Str::limit($contenu->titre, 50) }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit(strip_tags($contenu->texte), 100) }}</p>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $contenu->date_creation->format('d/m/Y') }}
                                </small>
                                @if($contenu->est_premium)
                                    @auth
                                        @php
                                            $dejaAchete = \App\Models\Paiement::where('id_utilisateur', auth()->user()->id_utilisateur)
                                                ->where('id_contenu', $contenu->id_contenu)
                                                ->where('statut', 'paye')
                                                ->exists();
                                        @endphp
                                        @if($dejaAchete || auth()->user()->isAdmin() || auth()->user()->id_utilisateur == $contenu->id_auteur)
                                            <a href="{{ route('contenus.show.public', $contenu->id_contenu) }}" class="btn btn-sm btn-outline-primary">
                                                Voir plus
                                            </a>
                                        @else
                                            <a href="{{ route('contenus.acheter.show', $contenu->id_contenu) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-lock-fill me-1"></i>Acheter
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('contenus.acheter.show', $contenu->id_contenu) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-lock-fill me-1"></i>Acheter
                                        </a>
                                    @endauth
                                @else
                                    <a href="{{ route('contenus.show.public', $contenu->id_contenu) }}" class="btn btn-sm btn-outline-primary">
                                        Voir plus
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5" style="min-width: 100%;">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h3 class="text-muted mt-3">Aucun contenu disponible</h3>
                    <p class="text-muted">Revenez bientôt pour découvrir nos contenus culturels.</p>
                </div>
            @endforelse
        </div>

        @if($contenusRecents->count() > 0)
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="{{ route('contenus.public') }}" class="btn btn-outline-primary">
                        Voir tous les contenus
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Régions -->
<section class="regions-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title">Nos Régions</h2>
                <p class="section-subtitle">Explorez la diversité culturelle par région</p>
            </div>
        </div>

        <div id="regions-scroll" class="d-flex flex-row overflow-x-auto pb-3" style="scroll-behavior: smooth;">
            @foreach($regions->take(6) as $region)
                <div class="card border-0 shadow-sm me-3" style="min-width: 300px; flex-shrink: 0; transition: transform 0.3s ease;">
                    <div class="card-body text-center">
                        <i class="bi bi-geo-alt display-4 text-primary mb-3"></i>
                        <h5 class="card-title fw-bold">{{ $region->nom_region }}</h5>
                        <p class="card-text text-muted">
                            {{ $region->description ? Str::limit($region->description, 100) : 'Découvrez la richesse culturelle de cette région' }}
                        </p>
                        <div class="mt-3">
                            <span class="badge bg-primary fs-6">
                                <i class="bi bi-file-text me-1"></i>
                                {{ $region->contenus_count ?? $region->contenus()->where('statut', 'valide')->count() }} contenu{{ ($region->contenus_count ?? 0) > 1 ? 's' : '' }}
                            </span>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('contenus.public', ['region' => $region->id_region]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-arrow-right me-1"></i>Explorer
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


@endsection
