@extends('layout')

@section('Content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Types de Medias</h1>
    <a href="{{ route('type_medias.create') }}" class="btn btn-success">Créer</a>
</div>

<div class="table-responsive">
<table class="table table-striped table-hover align-middle">
    <thead class="table-primary">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($typeMedias as $type)
        <tr>
            <td>{{ $type->id_type_media }}</td>
            <td>{{ $type->nom_media }}</td>
            <td class="table-actions">
                <a href="{{ route('type_medias.edit', $type->id_type_media) }}" class="btn btn-warning btn-sm">Éditer</a>
                <form action="{{ route('type_medias.destroy', $type->id_type_media) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ?')">Supprimer</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
@endsection
