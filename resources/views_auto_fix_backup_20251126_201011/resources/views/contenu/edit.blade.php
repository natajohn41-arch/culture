@extends('layouts.app')

@section('title', 'Éditer un contenu - Culture CMS')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="bi bi-file-text me-2"></i>Éditer le contenu</h1>
    <a href="{{ route('contenu.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
</div>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Détails du contenu
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contenu.update', $contenu->id_contenu) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" name="titre" id="titre" value="{{ old('titre', $contenu->titre) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="id_region" class="form-label">Région</label>
                            <select name="id_region" id="id_region" class="form-select" required>
                                <option value="" disabled>-- Sélectionner une région --</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id_region }}" {{ old('id_region', $contenu->id_region) == $region->id ? 'selected' : '' }}>
                                        {{ $region->nom_region }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date_creation" class="form-label">Date de création</label>
                            <input type="date" name="date_creation" id="date_creation" value="{{ old('date_creation', $contenu->date_creation) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="texte" class="form-label">Texte</label>
                            <textarea name="texte" id="texte" class="form-control" rows="4" required>{{ old('texte', $contenu->texte) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut</label>
                            <input type="text" name="statut" id="statut" value="{{ old('statut', $contenu->statut) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="id_langue" class="form-label">Langue</label>
                            <select name="id_langue" id="id_langue" class="form-select" required>
                                <option value="" disabled>-- Sélectionner une langue --</option>
                                @foreach($langues as $langue)
                                    <option value="{{ $langue->id_langue }}" {{ old('id_langue', $contenu->id_langue) == $langue->id ? 'selected' : '' }}>
                                        {{ $langue->nom_langue }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="id_auteur" class="form-label">Auteur</label>
                            <select name="id_auteur" id="id_auteur" class="form-select" required>
                                <option value="" disabled>-- Sélectionner un auteur --</option>
                                @foreach($utilisateurs as $user)
                                    <option value="{{ $user->id_utilisateur }}" {{ old('id_auteur', $contenu->id_auteur) == $user->id ? 'selected' : '' }}>
                                        {{ $user->nom }} {{ $user->prenom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date_validation" class="form-label">Date de validation</label>
                            <input type="date" name="date_validation" id="date_validation" value="{{ old('date_validation', $contenu->date_validation) }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="id_moderateur" class="form-label">Modérateur</label>
                            <select name="id_moderateur" id="id_moderateur" class="form-select">
                                <option value="" selected>-- Aucun --</option>
                                @foreach($utilisateurs as $user)
                                    <option value="{{ $user->id }}" {{ old('id_moderateur', $contenu->id_moderateur) == $user->id ? 'selected' : '' }}>
                                        {{ $user->nom }} {{ $user->prenom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Parent (contenu)</label>
                            <select name="parent_id" id="parent_id" class="form-select">
                                <option value="" selected>-- Aucun --</option>
                                @foreach($contenus as $c)
                                    <option value="{{ $c->id_contenu }}" {{ old('parent_id', $contenu->parent_id) == $c->id_contenu ? 'selected' : '' }}>
                                        {{ $c->titre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('contenu.index') }}" class="btn btn-secondary" title="Annuler">
                                <i class="bi bi-x-lg"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary" title="Enregistrer">
                                <i class="bi bi-save"></i> Enregistrer
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
