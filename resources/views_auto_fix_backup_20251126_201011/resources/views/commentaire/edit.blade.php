@extends('layouts.app')

@section('title', 'Modifier le Commentaire')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Modifier le Commentaire</h5>
                        <a href="{{ route('contenus.show', $commentaire->id_contenu) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour au contenu
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('commentaire.update', $commentaire->id_commentaire) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Informations sur le contenu -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Commentaire sur :</h6>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="{{ route('contenus.show', $commentaire->id_contenu) }}" 
                                               class="text-decoration-none">
                                                {{ $commentaire->contenu->titre }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            {{ $commentaire->contenu->region->nom_region }} • 
                                            {{ $commentaire->contenu->langue->nom_langue }} • 
                                            {{ $commentaire->contenu->typeContenu->nom_contenu }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="texte" class="form-label">Commentaire *</label>
                            <textarea class="form-control @error('texte') is-invalid @enderror" 
                                      id="texte" name="texte" rows="6" required>{{ old('texte', $commentaire->texte) }}</textarea>
                            @error('texte')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                            <div class="form-text">
                                Votre commentaire sera visible par tous les utilisateurs.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">Note (optionnelle)</label>
                            <select class="form-select @error('note') is-invalid @enderror" 
                                    id="note" name="note">
                                <option value="">Sans note</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('note', $commentaire->note) == $i ? 'selected' : '' }}>
                                        {{ $i }} étoile{{ $i > 1 ? 's' : '' }}
                                    </option>
                                @endfor
                            </select>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>

                        <!-- Informations du commentaire -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Informations du commentaire</h6>
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Auteur</small>
                                        <strong>{{ $commentaire->utilisateur->prenom }} {{ $commentaire->utilisateur->nom }}</strong>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Date de publication</small>
                                        <strong>{{ $commentaire->date->format('d/m/Y à H:i') }}</strong>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Statut</small>
                                        <span class="badge bg-success">Publié</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('contenus.show', $commentaire->id_contenu) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Annuler
                                    </a>
                                    <div class="btn-group">
                                        <a href="{{ route('commentaire.show', $commentaire->id_commentaire) }}" 
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