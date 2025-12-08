@extends('layouts.public')

@section('title', 'Accueil - Culture Bénin')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container text-center">
        <h1 class="display-3 fw-bold mb-4">Découvrez la Culture Béninoise</h1>
        <p class="lead mb-4">Explorez la richesse culturelle, linguistique et historique des régions du Bénin</p>
        <a href="{{ route('contenus.public') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-compass me-2"></i>Explorer les contenus
        </a>
    </div>
</section>

<!-- Statistiques -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-globe-americas display-4 text-primary"></i>
                        <h3 class="mt-3">{{ $totalRegions ?? 0 }}</h3>
                        <p class="text-muted">Régions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-translate display-4 text-success"></i>
                        <h3 class="mt-3">{{ $totalLangues ?? 0 }}</h3>
                        <p class="text-muted">Langues</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-file-text display-4 text-warning"></i>
                        <h3 class="mt-3">{{ $totalContenus ?? 0 }}</h3>
                        <p class="text-muted">Contenus</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-people display-4 text-info"></i>
                        <h3 class="mt-3">{{ $totalUtilisateurs ?? 0 }}</h3>
                        <p class="text-muted">Membres</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contenus Récents -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Contenus Récents</h2>
                <p class="text-muted">Découvrez les derniers contenus ajoutés</p>
            </div>
        </div>

        <div id="contenus-scroll" class="d-flex flex-row overflow-x-auto pb-3" style="scroll-behavior: smooth;">
            @forelse($contenusRecents as $contenu)
                <div class="card culture-card me-3" style="min-width: 300px; flex-shrink: 0;">
                    @if($contenu->medias->first() && str_starts_with($contenu->medias->first()->mime_type ?? '', 'image/'))
                        <img src="{{ asset('storage/' . $contenu->medias->first()->chemin) }}"
                             class="card-img-top" alt="{{ $contenu->titre }}" style="height: 200px; object-fit: cover;"
                             onerror="this.src='https://via.placeholder.com/300x200?text=Image+non+disponible'">
                    @elseif($contenu->medias->first())
                        @php $firstMedia = $contenu->medias->first(); @endphp
                        @if(str_starts_with($firstMedia->mime_type ?? '', 'video/'))
                            <div class="card-img-top position-relative bg-dark" style="height: 200px; overflow: hidden;">
                                <video src="{{ asset('storage/' . $firstMedia->chemin) }}" 
                                       class="w-100 h-100" 
                                       style="object-fit: cover;"
                                       controls
                                       preload="metadata">
                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                </video>
                            </div>
                        @elseif(str_starts_with($firstMedia->mime_type ?? '', 'audio/'))
                            <div class="card-img-top bg-light d-flex flex-column align-items-center justify-content-center p-3" style="height: 200px;">
                                <i class="bi bi-music-note-beamed text-primary mb-2" style="font-size: 2.5rem;"></i>
                                <audio src="{{ asset('storage/' . $firstMedia->chemin) }}" controls style="width: 100%;"></audio>
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
                        </div>

                        <h5 class="card-title">{{ Str::limit($contenu->titre, 50) }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit(strip_tags($contenu->texte), 100) }}</p>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $contenu->date_creation->format('d/m/Y') }}
                                </small>
                                <a href="{{ route('contenus.show.public', $contenu->id_contenu) }}" class="btn btn-sm btn-outline-primary">
                                    Voir plus
                                </a>
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
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Nos Régions</h2>
                <p class="text-muted">Explorez la diversité culturelle par région</p>
            </div>
        </div>

        <div id="regions-scroll" class="d-flex flex-row overflow-x-auto pb-3" style="scroll-behavior: smooth;">
            @foreach($regions->take(6) as $region)
                <div class="card border-0 shadow-sm me-3" style="min-width: 300px; flex-shrink: 0;">
                    <div class="card-body text-center">
                        <i class="bi bi-geo-alt display-4 text-primary mb-3"></i>
                        <h5 class="card-title">{{ $region->nom_region }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($region->description, 100) }}
                        </p>
                        <small class="text-muted">
                            {{ $region->contenus_count ?? 0 }} contenus
                        </small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


@endsection
