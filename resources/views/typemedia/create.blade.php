@extends('layouts.app')

@section('title', 'Créer un Type de Média')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Créer un Type de Média</h5>
                        <a href="{{ route('type-medias.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('type-medias.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nom_media" class="form-label">Nom du type *</label>
                            <input type="text" class="form-control @error('nom_media') is-invalid @enderror" 
                                   id="nom_media" name="nom_media" value="{{ old('nom_media') }}" 
                                   placeholder="Ex: Image, Vidéo, Audio, Document..." required>
                            @error('nom_media')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Description du type de média...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="extensions" class="form-label">Extensions acceptées</label>
                            <input type="text" class="form-control @error('extensions') is-invalid @enderror" 
                                   id="extensions" name="extensions" value="{{ old('extensions') }}" 
                                   placeholder="Ex: jpg,png,gif,mp4,mp3,pdf (séparées par des virgules)">
                            @error('extensions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                            <div class="form-text">
                                Laisser vide pour accepter tous les formats. Séparer les extensions par des virgules.
                            </div>
                        </div>

                        <!-- Types prédéfinis -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="bi bi-lightbulb me-2"></i>Types courants
                            </h6>
                            <div class="row small">
                                <div class="col-md-6">
                                    <strong>Images :</strong> jpg, jpeg, png, gif, webp
                                </div>
                                <div class="col-md-6">
                                    <strong>Vidéos :</strong> mp4, avi, mov, webm
                                </div>
                                <div class="col-md-6">
                                    <strong>Audio :</strong> mp3, wav, ogg, m4a
                                </div>
                                <div class="col-md-6">
                                    <strong>Documents :</strong> pdf, doc, docx, txt
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