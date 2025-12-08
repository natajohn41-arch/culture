@extends('layouts.app')

@section('title', 'Créer un Rôle')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Créer un Nouveau Rôle</h5>
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nom_role" class="form-label">Nom du rôle *</label>
                            <input type="text" class="form-control @error('nom_role') is-invalid @enderror" 
                                   id="nom_role" name="nom_role" value="{{ old('nom_role') }}" 
                                   placeholder="Ex: Éditeur, Contributeur, Lecteur..." required>
                            @error('nom_role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Description des permissions de ce rôle...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>

                        <!-- Rôles existants pour référence -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="bi bi-info-circle me-2"></i>Rôles existants
                            </h6>
                            <div class="row small">
                                <div class="col-md-6">
                                    <strong>Admin :</strong> Accès complet
                                </div>
                                <div class="col-md-6">
                                    <strong>Modérateur :</strong> Validation des contenus
                                </div>
                                <div class="col-md-6">
                                    <strong>Auteur :</strong> Création de contenus
                                </div>
                                <div class="col-md-6">
                                    <strong>Utilisateur :</strong> Consultation et commentaires
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Créer le rôle
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