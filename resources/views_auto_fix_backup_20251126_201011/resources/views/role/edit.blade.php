@extends('layouts.app')

@section('title', 'Modifier le Rôle')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Modifier le Rôle</h5>
                        <a href="{{ route('role.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('role.update', $role->id_role) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nom_role" class="form-label">Nom du rôle *</label>
                            <input type="text" class="form-control @error('nom_role') is-invalid @enderror" 
                                   id="nom_role" name="nom_role" 
                                   value="{{ old('nom_role', $role->nom_role) }}" required>
                            @error('nom_role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>

                        <!-- Statistiques -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Statistiques</h6>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <h4 class="text-primary">{{ $role->utilisateurs_count }}</h4>
                                        <small class="text-muted">Utilisateurs</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-success">{{ $role->created_at->diffForHumans() }}</h4>
                                        <small class="text-muted">Créé</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Protection des rôles système -->
                        @if(in_array($role->nom_role, ['Admin', 'Moderateur', 'Auteur', 'Utilisateur']))
                            <div class="alert alert-warning">
                                <i class="bi bi-shield-exclamation me-2"></i>
                                Ce rôle est un rôle système et ne peut pas être supprimé.
                            </div>
                        @endif

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('role.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Annuler
                                    </a>
                                    <div class="btn-group">
                                        <a href="{{ route('role.show', $role->id_role) }}" 
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