@extends('layout')

@section('Content')

<div class="card shadow-lg border-0 mb-4">
    <div class="card-header bg-success text-white py-3">
        <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Créer un Contenu</h4>
    </div>

    <div class="card-body p-4">

        <form action="{{ route('contenus.store') }}" method="POST" class="row g-3">
            @csrf

            <div class="col-md-6">
                <label class="form-label fw-bold">Titre</label>
                <input type="text" name="titre" class="form-control shadow-sm" required>
            </div>

            <div class="col-md-12">
                <label class="form-label fw-bold">Texte</label>
                <textarea name="texte" class="form-control shadow-sm" rows="4"></textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Statut</label>
                <select name="statut" class="form-select shadow-sm" required>
                    <option value="Bon">Bon</option>
                    <option value="Médiocre">Médiocre</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Date de création</label>
                <input type="date" name="date_creation" class="form-control shadow-sm">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Date de validation</label>
                <input type="date" name="date_validation" class="form-control shadow-sm">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Contenu parent</label>
                <select name="parent_id" class="form-select shadow-sm">
                    <option value="">Aucun</option>
                    @foreach($parents as $p)
                        <option value="{{ $p->id_contenu }}">{{ $p->titre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Région</label>
                <select name="id_region" class="form-select shadow-sm" required>
                    @foreach($regions as $region)
                        <option value="{{ $region->id_region }}">{{ $region->nom_region }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Langue</label>
                <select name="id_langue" class="form-select shadow-sm" required>
                    @foreach($langues as $langue)
                        <option value="{{ $langue->id_langue }}">{{ $langue->nom_langue }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Type de contenu</label>
                <select name="id_type_contenu" class="form-select shadow-sm" required>
                    @foreach($types as $type)
                        <option value="{{ $type->id_type_contenu }}">{{ $type->nom_contenu }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Auteur</label>
                <select name="id_auteur" class="form-select shadow-sm" required>
                    @foreach($utilisateurs as $u)
                        <option value="{{ $u->id_utilisateur }}">{{ $u->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Modérateur</label>
                <select name="id_moderateur" class="form-select shadow-sm">
                    <option value="">Aucun</option>
                    @foreach($utilisateurs as $u)
                        <option value="{{ $u->id_utilisateur }}">{{ $u->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('contenus.index') }}" class="btn btn-outline-secondary px-4">Annuler</a>
                <button class="btn btn-success px-4">Créer</button>
            </div>

        </form>
    </div>
</div>

@endsection
