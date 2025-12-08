@extends('layout')

@section('Content')

<div class="card shadow-lg border-0 mb-4">
    <div class="card-header bg-warning text-dark py-3">
        <h4 class="mb-0">
            <i class="bi bi-pencil-square"></i> Éditer le Contenu
        </h4>
    </div>

    <div class="card-body p-4">

        <form action="{{ route('contenus.update', $contenu->id_contenu) }}" method="POST" class="row g-3">
            @csrf
            @method('PUT')

            <div class="col-md-6">
                <label class="form-label fw-bold">Titre</label>
                <input type="text" name="titre" class="form-control shadow-sm"
                       value="{{ $contenu->titre }}" required>
            </div>

            <div class="col-md-12">
                <label class="form-label fw-bold">Texte</label>
                <textarea name="texte" class="form-control shadow-sm" rows="4">{{ $contenu->texte }}</textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Statut</label>
                <select name="statut" class="form-select shadow-sm" required>
                    <option value="Bon" {{ $contenu->statut == 'Bon' ? 'selected' : '' }}>Bon</option>
                    <option value="Médiocre" {{ $contenu->statut == 'Médiocre' ? 'selected' : '' }}>Médiocre</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Date de création</label>
                <input type="date" name="date_creation" class="form-control shadow-sm"
                       value="{{ $contenu->date_creation }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Date de validation</label>
                <input type="date" name="date_validation" class="form-control shadow-sm"
                       value="{{ $contenu->date_validation }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Contenu parent</label>
                <select name="parent_id" class="form-select shadow-sm">
                    <option value="">Aucun</option>
                    @foreach($parents as $p)
                        <option value="{{ $p->id_contenu }}"
                            {{ $contenu->parent_id == $p->id_contenu ? 'selected' : '' }}>
                            {{ $p->titre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Région</label>
                <select name="id_region" class="form-select shadow-sm" required>
                    @foreach($regions as $region)
                        <option value="{{ $region->id_region }}"
                            {{ $contenu->id_region == $region->id_region ? 'selected' : '' }}>
                            {{ $region->nom_region }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Langue</label>
                <select name="id_langue" class="form-select shadow-sm" required>
                    @foreach($langues as $langue)
                        <option value="{{ $langue->id_langue }}"
                            {{ $contenu->id_langue == $langue->id_langue ? 'selected' : '' }}>
                            {{ $langue->nom_langue }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Type de contenu</label>
                <select name="id_type_contenu" class="form-select shadow-sm" required>
                    @foreach($types as $type)
                        <option value="{{ $type->id_type_contenu }}"
                            {{ $contenu->id_type_contenu == $type->id_type_contenu ? 'selected' : '' }}>
                            {{ $type->nom_contenu }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Auteur</label>
                <select name="id_auteur" class="form-select shadow-sm" required>
                    @foreach($utilisateurs as $u)
                        <option value="{{ $u->id_utilisateur }}"
                            {{ $contenu->id_auteur == $u->id_utilisateur ? 'selected' : '' }}>
                            {{ $u->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Modérateur</label>
                <select name="id_moderateur" class="form-select shadow-sm">
                    <option value="">Aucun</option>
                    @foreach($utilisateurs as $u)
                        <option value="{{ $u->id_utilisateur }}"
                            {{ $contenu->id_moderateur == $u->id_utilisateur ? 'selected' : '' }}>
                            {{ $u->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('contenus.index') }}" class="btn btn-outline-secondary px-4">Annuler</a>
                <button class="btn btn-warning px-4 text-dark fw-bold">Mettre à jour</button>
            </div>

        </form>
    </div>
</div>

@endsection
