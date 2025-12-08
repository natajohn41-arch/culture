@extends('layouts.public')

@section('title', $contenu->titre . ' - Culture Bénin')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('accueil') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contenus.public') }}">Contenus</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($contenu->titre, 30) }}</li>
                </ol>
            </nav>

            <article class="card shadow-sm border-0">
                @if($contenu->medias->first())
                    @php $firstMedia = $contenu->medias->first(); @endphp
                    @if(str_starts_with($firstMedia->mime_type ?? '', 'image/'))
                        <img src="{{ asset('storage/' . $firstMedia->chemin) }}" 
                             class="card-img-top" alt="{{ $contenu->titre }}" 
                             style="max-height: 500px; object-fit: cover;"
                             onerror="this.src='https://via.placeholder.com/800x400?text=Image+non+disponible'">
                    @elseif(str_starts_with($firstMedia->mime_type ?? '', 'video/'))
                        <div class="card-img-top bg-dark">
                            <video src="{{ asset('storage/' . $firstMedia->chemin) }}" 
                                   class="w-100" 
                                   style="max-height: 500px;"
                                   controls
                                   preload="metadata">
                                Votre navigateur ne supporte pas la lecture de vidéos.
                            </video>
                        </div>
                    @elseif(str_starts_with($firstMedia->mime_type ?? '', 'audio/'))
                        <div class="card-img-top bg-light p-4 text-center">
                            <i class="bi bi-music-note-beamed text-primary" style="font-size: 4rem;"></i>
                            <audio src="{{ asset('storage/' . $firstMedia->chemin) }}" 
                                   controls 
                                   class="w-100 mt-3">
                                Votre navigateur ne supporte pas la lecture audio.
                            </audio>
                        </div>
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                            <div class="text-center">
                                <i class="bi bi-file-earmark text-muted" style="font-size: 4rem;"></i>
                                <p class="text-muted mt-2">{{ $firstMedia->description ?? 'Document' }}</p>
                                <a href="{{ asset('storage/' . $firstMedia->chemin) }}" 
                                   class="btn btn-primary" 
                                   target="_blank" 
                                   download>
                                    <i class="bi bi-download me-2"></i>Télécharger
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
                
                <div class="card-body p-4">
                    <div class="mb-3">
                        @foreach($contenu->medias as $media)
                            <span class="badge region-badge me-2">{{ $contenu->region->nom_region }}</span>
                            <span class="badge bg-info me-2">{{ $contenu->langue->nom_langue }}</span>
                            <span class="badge bg-secondary">{{ $contenu->typeContenu->nom_contenu }}</span>
                        @endforeach
                    </div>

                    <h1 class="card-title display-6 fw-bold mb-3">{{ $contenu->titre }}</h1>
                    
                    <div class="d-flex align-items-center text-muted mb-4">
                        <small class="me-3">
                            <i class="bi bi-person me-1"></i>
                            Par {{ $contenu->auteur->prenom }} {{ $contenu->auteur->nom }}
                        </small>
                        <small>
                            <i class="bi bi-calendar me-1"></i>
                            {{ $contenu->date_creation->format('d/m/Y à H:i') }}
                        </small>
                    </div>

                    <div class="content-text">
                        {!! $contenu->texte !!}
                    </div>

                    <!-- Galerie des médias -->
                    @if($contenu->medias->count() > 1)
                        <div class="mt-5">
                            <h5 class="mb-3">Galerie</h5>
                            <div class="row g-3">
                                @foreach($contenu->medias->skip(1) as $media)
                                    <div class="col-md-4">
                                        @if(str_starts_with($media->mime_type ?? '', 'image/'))
                                            <img src="{{ asset('storage/' . $media->chemin) }}" 
                                                 class="img-thumbnail w-100" 
                                                 alt="{{ $media->description }}"
                                                 style="height: 150px; object-fit: cover;"
                                                 onerror="this.src='https://via.placeholder.com/200x150?text=Image'">
                                        @elseif(str_starts_with($media->mime_type ?? '', 'video/'))
                                            <div class="img-thumbnail position-relative bg-dark" style="height: 150px; overflow: hidden;">
                                                <video src="{{ asset('storage/' . $media->chemin) }}" 
                                                       class="w-100 h-100" 
                                                       style="object-fit: cover;"
                                                       controls
                                                       preload="metadata">
                                                </video>
                                            </div>
                                        @elseif(str_starts_with($media->mime_type ?? '', 'audio/'))
                                            <div class="img-thumbnail bg-light d-flex flex-column align-items-center justify-content-center p-2" style="height: 150px;">
                                                <i class="bi bi-music-note-beamed text-primary mb-2" style="font-size: 2rem;"></i>
                                                <audio src="{{ asset('storage/' . $media->chemin) }}" controls style="width: 100%;"></audio>
                                            </div>
                                        @else
                                            <div class="img-thumbnail bg-light d-flex flex-column align-items-center justify-content-center" style="height: 150px;">
                                                <i class="bi bi-file-earmark text-muted mb-2" style="font-size: 2rem;"></i>
                                                <small class="text-muted text-center">{{ Str::limit($media->description ?? 'Document', 20) }}</small>
                                                <a href="{{ asset('storage/' . $media->chemin) }}" 
                                                   class="btn btn-sm btn-primary mt-2" 
                                                   target="_blank" 
                                                   download>
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </article>

            <!-- Commentaires -->
            <section class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-chat-text me-2"></i>
                        Commentaires ({{ $contenu->commentaires->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @auth
                        <form action="{{ route('commentaires.store') }}" method="POST" class="mb-4">
                            @csrf
                            <input type="hidden" name="id_contenu" value="{{ $contenu->id_contenu }}">
                            <div class="mb-3">
                                <label for="texte" class="form-label">Votre commentaire</label>
                                <textarea class="form-control" id="texte" name="texte" rows="3" required></textarea>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="note" class="form-label">Note (optionnelle)</label>
                                        <select class="form-select" id="note" name="note">
                                            <option value="">Sans note</option>
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}">{{ $i }} étoile{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send me-2"></i>Publier
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info text-center">
                            <a href="{{ route('login') }}" class="btn btn-primary me-2">Connectez-vous</a>
                            pour laisser un commentaire
                        </div>
                    @endauth

                    <!-- Liste des commentaires -->
                    <div class="comment-list">
                        @forelse($contenu->commentaires->sortByDesc('date') as $commentaire)
                            <div class="comment-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong class="d-block">{{ $commentaire->utilisateur->prenom }} {{ $commentaire->utilisateur->nom }}</strong>
                                        <small class="text-muted">
                                            {{ $commentaire->date->format('d/m/Y à H:i') }}
                                        </small>
                                    </div>
                                    @if($commentaire->note)
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $commentaire->note ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                    @endif
                                </div>
                                <p class="mb-0">{{ $commentaire->texte }}</p>
                                
                                @auth
                                    @if(auth()->user()->id_utilisateur == $commentaire->id_utilisateur || auth()->user()->isAdmin())
                                        <form action="{{ route('commentaires.destroy', $commentaire->id_commentaire) }}" method="POST" class="mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce commentaire ?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        @empty
                            <p class="text-muted text-center">Aucun commentaire pour le moment.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Auteur -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">À propos de l'auteur</h6>
                </div>
                <div class="card-body text-center">
                    @if($contenu->auteur->photo)
                        <img src="{{ asset('storage/' . $contenu->auteur->photo) }}" 
                             class="rounded-circle mb-3" 
                             alt="{{ $contenu->auteur->prenom }}"
                             style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3 text-white" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-person-fill" style="font-size: 2rem;"></i>
                        </div>
                    @endif
                    <h6>{{ $contenu->auteur->prenom }} {{ $contenu->auteur->nom }}</h6>
                    <p class="text-muted small">Membre depuis {{ $contenu->auteur->date_inscription->format('m/Y') }}</p>
                </div>
            </div>

            <!-- Contenus similaires -->
            @if($contenusSimilaires->count() > 0)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Contenus similaires</h6>
                    </div>
                    <div class="card-body">
                        @foreach($contenusSimilaires as $similaire)
                            <div class="mb-3 pb-3 border-bottom">
                                <h6 class="mb-1">
                                    <a href="{{ route('contenus.show.public', $similaire->id_contenu) }}" class="text-decoration-none">
                                        {{ Str::limit($similaire->titre, 50) }}
                                    </a>
                                </h6>
                                <div class="small text-muted">
                                    <span class="badge bg-light text-dark">{{ $similaire->region->nom_region }}</span>
                                    <span class="badge bg-light text-dark">{{ $similaire->langue->nom_langue }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.content-text {
    line-height: 1.8;
    font-size: 1.1rem;
}

.content-text p {
    margin-bottom: 1rem;
}

.content-text img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}
</style>
@endsection