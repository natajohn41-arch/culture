@extends('layouts.app')

@section('title', 'Modifier le Type de Contenu')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Modifier le Type de Contenu</h5>
                        <a href="{{ route('type-contenus.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('type-contenus.update', $typeContenu->id_type_contenu) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nom_contenu" class="form-label">Nom du type *</label>
                            <input type="text" class="form-control @error('nom_contenu') is-invalid @enderror" 
                                   id="nom_contenu" name="nom_contenu" 
                                   value="{{ old('nom_contenu', $typeContenu->nom_contenu) }}" required>
                            @error('nom_contenu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $typeContenu->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Statistiques -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Statistiques</h6>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <h4 class="text-primary">{{ $typeContenu->contenus_count }}</h4>
                                        <small class="text-muted">Contenus associés</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-success">{{ $typeContenu->created_at->diffForHumans() }}</h4>
                                        <small class="text-muted">Créé</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('type-contenus.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Annuler
                                    </a>
                                    <div class="btn-group">
                                        <a href="{{ route('type-contenus.show', $typeContenu->id_type_contenu) }}" 
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