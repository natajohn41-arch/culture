@extends('layouts.app')

@section('title', 'Gestion des Médias')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3">Gestion des Médias</h1>
                    <p class="text-muted mb-0">Bibliothèque de tous les médias de la plateforme</p>
                </div>
                @can('create', App\Models\Media::class)
                    <a href="{{ route('media.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Nouveau Média
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('media.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="type" class="form-label">Type de média</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">Tous les types</option>
                                @foreach($types = \App\Models\TypeMedia::all() as $type)
                                    <option value="{{ $type->id_type_media }}" {{ request('type') == $type->id_type_media ? 'selected' : '' }}>
                                        {{ $type->nom_media }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="contenu" class="form-label">Contenu</label>
                            <select name="contenu" id="contenu" class="form-select">
                                <option value="">Tous les contenus</option>
                                @foreach($contenus = \App\Models\Contenu::all() as $contenu)
                                    <option value="{{ $contenu->id_contenu }}" {{ request('contenu') == $contenu->id_contenu ? 'selected' : '' }}>
                                        {{ Str::limit($contenu->titre, 30) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-funnel me-2"></i>Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Galerie des médias -->
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="mb-0">Médias ({{ $medias->count() }})</h6>
        </div>
        <div class="card-body">
            @if($medias->count() > 0)
                <div class="row">
                    @foreach($medias as $media)
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <!-- Prévisualisation -->
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                                     style="height: 200px; overflow: hidden;">
                                    @if(str_contains($media->chemin, 'image/') || in_array(pathinfo($media->chemin, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <img src="{{ asset('storage/' . $media->chemin) }}" 
                                             class="img-fluid" 
                                             style="max-height: 100%; object-fit: cover;"
                                             alt="{{ $media->description }}">
                                    @elseif(str_contains($media->chemin, 'video/') || in_array(pathinfo($media->chemin, PATHINFO_EXTENSION), ['mp4', 'avi', 'mov']))
                                        <div class="text-center text-muted">
                                            <i class="bi bi-camera-video display-4"></i>
                                            <div class="mt-2">Vidéo</div>
                                        </div>
                                    @elseif(str_contains($media->chemin, 'audio/') || in_array(pathinfo($media->chemin, PATHINFO_EXTENSION), ['mp3', 'wav', 'ogg']))
                                        <div class="text-center text-muted">
                                            <i class="bi bi-mic display-4"></i>
                                            <div class="mt-2">Audio</div>
                                        </div>
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="bi bi-file-earmark display-4"></i>
                                            <div class="mt-2">Document</div>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <h6 class="card-title">{{ Str::limit($media->description, 40) }}</h6>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <strong>Type :</strong> {{ $media->typeMedia->nom_media }}
                                        </small>
                                    </div>

                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <strong>Contenu :</strong> 
                                            <a href="{{ route('contenu.show', $media->id_contenu) }}" class="text-decoration-none">
                                                {{ Str::limit($media->contenu->titre, 25) }}
                                            </a>
                                        </small>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <strong>Taille :</strong> 
                                            {{ round(filesize(storage_path('app/public/' . $media->chemin)) / 1024 / 1024, 2) }} MB
                                        </small>
                                    </div>
                                </div>

                                <div class="card-footer bg-white">
                                    <div class="btn-group w-100">
                                        <a href="{{ route('media.show', $media->id_media) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        @can('update', $media)
                                            <a href="{{ route('media.edit', $media->id_media) }}" 
                                               class="btn btn-outline-warning btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan

                                        <a href="{{ route('media.download', $media->id_media) }}" 
                                           class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-download"></i>
                                        </a>

                                        @can('delete', $media)
                                            <form action="{{ route('media.destroy', $media->id_media) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm('Supprimer ce média ?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-images display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">Aucun média</h4>
                    <p class="text-muted">Aucun média n'a été uploadé pour le moment.</p>
                    @can('create', App\Models\Media::class)
                        <a href="{{ route('media.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Ajouter le premier média
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection