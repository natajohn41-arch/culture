@extends('layouts.app')

@section('title', 'Contenus à Valider')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3">Contenus à Valider</h1>
                    <p class="text-muted mb-0">Validez ou rejetez les contenus soumis par les auteurs</p>
                </div>
                <span class="badge bg-warning fs-6">{{ $contenus->count() }} en attente</span>
            </div>
        </div>
    </div>

    @if($contenus->count() > 0)
        <div class="row">
            @foreach($contenus as $contenu)
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 border-warning">
                        <div class="card-header bg-warning text-dark">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ Str::limit($contenu->titre, 60) }}</h6>
                                <small class="badge bg-dark">{{ $contenu->typeContenu->nom_contenu }}</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Auteur :</strong> 
                                {{ $contenu->auteur->prenom }} {{ $contenu->auteur->nom }}
                                <br>
                                <strong>Région :</strong> {{ $contenu->region->nom_region }}
                                <br>
                                <strong>Langue :</strong> {{ $contenu->langue->nom_langue }}
                                <br>
                                <strong>Soumis le :</strong> {{ $contenu->date_creation->format('d/m/Y à H:i') }}
                            </div>

                            <div class="mb-3">
                                <strong>Contenu :</strong>
                                <div class="border rounded p-3 bg-light mt-2" style="max-height: 200px; overflow-y: auto;">
                                    {{ Str::limit(strip_tags($contenu->texte), 500) }}
                                </div>
                            </div>

                            @if($contenu->medias->count() > 0)
                                <div class="mb-3">
                                    <strong>Médias :</strong>
                                    <div class="row g-2 mt-2">
                                        @foreach($contenu->medias->take(3) as $media)
                                            <div class="col-4">
                                                @if(str_starts_with($media->mime_type ?? '', 'image/'))
                                                    <img src="{{ asset('storage/' . $media->chemin) }}" 
                                                         class="img-thumbnail w-100" 
                                                         style="height: 80px; object-fit: cover;"
                                                         onerror="this.src='https://via.placeholder.com/80x80?text=Image'">
                                                @elseif(str_starts_with($media->mime_type ?? '', 'video/'))
                                                    <div class="border rounded bg-dark position-relative" style="height: 80px; overflow: hidden;">
                                                        <video src="{{ asset('storage/' . $media->chemin) }}" 
                                                               class="w-100 h-100" 
                                                               style="object-fit: cover;"
                                                               controls
                                                               preload="metadata">
                                                        </video>
                                                    </div>
                                                @elseif(str_starts_with($media->mime_type ?? '', 'audio/'))
                                                    <div class="border rounded p-1 bg-light text-center" style="height: 80px;">
                                                        <i class="bi bi-music-note-beamed text-primary"></i>
                                                        <audio src="{{ asset('storage/' . $media->chemin) }}" controls style="width: 100%; height: 30px;"></audio>
                                                    </div>
                                                @else
                                                    <div class="border rounded p-2 text-center bg-white" style="height: 80px;">
                                                        <i class="bi bi-file-earmark"></i>
                                                        <small class="d-block">{{ pathinfo($media->chemin, PATHINFO_EXTENSION) }}</small>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                        @if($contenu->medias->count() > 3)
                                            <div class="col-4">
                                                <div class="border rounded p-2 text-center bg-light h-100 d-flex align-items-center justify-content-center">
                                                    +{{ $contenu->medias->count() - 3 }} autres
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('contenus.show', $contenu->id_contenu) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Voir en détail
                                </a>
                                <div class="btn-group">
                                    <form action="{{ route('contenus.valider', $contenu->id_contenu) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-check me-1"></i>Valider
                                        </button>
                                    </form>
                                    <form action="{{ route('contenus.rejeter', $contenu->id_contenu) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Rejeter ce contenu ?')">
                                            <i class="bi bi-x me-1"></i>Rejeter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-check-circle display-1 text-success"></i>
            <h3 class="text-success mt-3">Aucun contenu en attente</h3>
            <p class="text-muted">Tous les contenus ont été validés ou rejetés.</p>
            <a href="{{ route('contenus.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Retour aux contenus
            </a>
        </div>
    @endif
</div>
@endsection