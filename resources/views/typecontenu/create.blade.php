@extends('layouts.app')

@section('title', 'Créer un Type de Contenu')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Créer un Type de Contenu</h5>
                        <a href="{{ route('type-contenus.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('type-contenus.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nom_contenu" class="form-label">Nom du type *</label>
                            <input type="text" class="form-control @error('nom_contenu') is-invalid @enderror" 
                                   id="nom_contenu" name="nom_contenu" value="{{ old('nom_contenu') }}" 
                                   placeholder="Ex: Article, Vidéo, Audio, Document..." required>
                            @error('nom_contenu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Description du type de contenu...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Exemples de types -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="bi bi-lightbulb me-2"></i>Suggestions
                            </h6>
                            <small class="mb-0">
                                Types courants : Article, Reportage, Interview, Documentaire, 
                                Photographie, Chanson, Poème, Recette, Tradition, Cérémonie...
                            </small>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('type-contenus.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Créer le type
                                    </button>
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