@extends('layouts.app')

@section('title', 'Modifier le Type de Média')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Modifier le Type de Média</h5>
                        <a href="{{ route('type-medias.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('type-medias.update', $typeMedia->id_type_media) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nom_media" class="form-label">Nom du type *</label>
                            <input type="text" class="form-control @error('nom_media') is-invalid @enderror" 
                                   id="nom_media" name="nom_media" 
                                   value="{{ old('nom_media', $typeMedia->nom_media) }}" required>
                            @error('nom_media')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $typeMedia->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="extensions" class="form-label">Extensions acceptées</label>
                            <input type="text" class="form-control @error('extensions') is-invalid @enderror" 
                                   id="extensions" name="extensions" 
                                   value="{{ old('extensions', $typeMedia->extensions) }}"
                                   placeholder="Ex: jpg,png,gif,mp4,mp3,pdf">
                            @error('extensions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>

                        <!-- Statistiques -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Statistiques</h6>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <h4 class="text-primary">{{ $typeMedia->medias_count }}</h4>
                                        <small class="text-muted">Médias associés</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-success">{{ $typeMedia->created_at->diffForHumans() }}</h4>
                                        <small class="text-muted">Créé</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('type-medias.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Annuler
                                    </a>
                                    <div class="btn-group">
                                        <a href="{{ route('type-medias.show', $typeMedia->id_type_media) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="bi bi-eye me-2"></i>Voir
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-2"></i>Modifier
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection