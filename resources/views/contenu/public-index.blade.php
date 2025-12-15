@extends('layouts.public')

@section('title', 'Contenus Culturels - Culture Bénin')

@section('content')
<div class="container py-5">
    <!-- En-tête -->
    <div class="row mb-5 animate-fade-in-down">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold text-dark mb-3">Contenus Culturels du Bénin</h1>
            <p class="lead text-muted">Découvrez la richesse culturelle et linguistique de nos régions</p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4 animate-fade-in-up">
        <div class="col-12">
            <div class="card shadow-sm hover-lift">
                <div class="card-body">
                    <form action="{{ route('contenus.public') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="region" class="form-label">Région</label>
                            <select name="region" id="region" class="form-select">
                                <option value="">Toutes les régions</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id_region }}" {{ request('region') == $region->id_region ? 'selected' : '' }}>
                                        {{ $region->nom_region }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="langue" class="form-label">Langue</label>
                            <select name="langue" id="langue" class="form-select">
                                <option value="">Toutes les langues</option>
                                @foreach($langues as $langue)
                                    <option value="{{ $langue->id_langue }}" {{ request('langue') == $langue->id_langue ? 'selected' : '' }}>
                                        {{ $langue->nom_langue }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="type" class="form-label">Type de contenu</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">Tous les types</option>
                                @foreach($typesContenu as $type)
                                    <option value="{{ $type->id_type_contenu }}" {{ request('type') == $type->id_type_contenu ? 'selected' : '' }}>
                                        {{ $type->nom_contenu }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel me-2"></i>Filtrer
                            </button>
                            <a href="{{ route('contenus.public') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des contenus -->
    <div class="row">
        @forelse($contenus as $index => $contenu)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card culture-card h-100 hover-lift fade-in-delay-{{ min(($index % 4) + 1, 4) }}">
                    @if($contenu->medias->first())
                        @php $firstMedia = $contenu->medias->first(); @endphp
                        @if(str_starts_with($firstMedia->mime_type ?? '', 'image/'))
                            <img src="{{ asset('storage/' . $firstMedia->chemin) }}" 
                                 class="card-img-top" alt="{{ $contenu->titre }}" 
                                 style="height: 200px; object-fit: cover;"
                                 onerror="this.src='https://via.placeholder.com/400x200?text=Image+non+disponible'">
                        @elseif(str_starts_with($firstMedia->mime_type ?? '', 'video/'))
                            <div class="card-img-top position-relative" style="height: 200px; background: #000;">
                                <video src="{{ asset('storage/' . $firstMedia->chemin) }}" 
                                       class="w-100 h-100" 
                                       style="object-fit: cover;"
                                       controls
                                       preload="metadata">
                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                </video>
                            </div>
                        @elseif(str_starts_with($firstMedia->mime_type ?? '', 'audio/'))
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <div class="text-center">
                                    <i class="bi bi-music-note-beamed text-primary" style="font-size: 3rem;"></i>
                                    <audio src="{{ asset('storage/' . $firstMedia->chemin) }}" controls class="mt-2 w-100"></audio>
                                </div>
                            </div>
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-file-earmark text-muted" style="font-size: 3rem;"></i>
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
                            <span class="badge bg-secondary">{{ $contenu->typeContenu->nom_contenu }}</span>
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
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h3 class="text-muted mt-3">Aucun contenu trouvé</h3>
                <p class="text-muted">Aucun contenu ne correspond à vos critères de recherche.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($contenus->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <nav>
                    {{ $contenus->links() }}
                </nav>
            </div>
        </div>
    @endif
</div>
@endsection