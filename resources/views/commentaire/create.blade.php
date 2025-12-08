@extends('layouts.app')

@section('title', 'Créer un commentaire - Culture CMS')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="bi bi-chat-text me-2"></i>Créer un commentaire</h1>
    <a href="{{ route('commentaires.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
</div>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Détails du commentaire
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

                    <form action="{{ route('commentaires.store') }}" method="POST">
                        @csrf
                         <div class="mb-3">
                            <label for="texte" class="form-label">Date</label>
                            <input type="date" name="date" id="date" class="form-control"  value="{{ old('date', $commentaire->date) }}" required  >
                        </div>

                        <div class="mb-3">
                            <label for="id_auteur" class="form-label">Utilisateur (ID)</label>
                            <input type="number" name="id_utilisateur" id="id_utilisateur" value="{{ old('id_utilisateur', $commentaire->id_utilisateur) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="id_contenu" class="form-label">Contenu (ID)</label>
                            <input type="number" name="id_contenu" id="id_contenu" value="{{ old('id_contenu', $commentaire->id_contenu) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="texte" class="form-label">Texte</label>
                            <textarea name="texte" id="texte" class="form-control" rows="4" required>{{ old('texte') }}</textarea>
                        </div>


                         <div class="mb-3">
                            <label for="texte" class="form-label">Note</label>
                            <input name="note" id="note" class="form-control" rows="4" required {{ old('note', $commentaire->note) }} >
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('commentaires.index') }}" class="btn btn-secondary" title="Annuler">
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
