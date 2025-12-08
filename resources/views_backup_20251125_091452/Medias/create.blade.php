@extends('layout')

@section('Content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h3>Créer un Média</h3>
    </div>

    <div class="card-body">
        <form action="{{ route('medias.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Fichier</label>
                <input type="file" name="chemin" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Type de média</label>
                <select name="id_type_media" class="form-select" required>
                    @foreach($typeMedias as $type)
                        <option value="{{ $type->id_type_media }}">{{ $type->nom_media }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex">
                <button type="submit" class="btn btn-success me-2">Créer</button>
                <a href="{{ route('medias.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
