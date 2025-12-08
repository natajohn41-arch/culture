@extends('layout')

@section('Content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-bold">Liste des Médias</h1>
    <a href="{{ route('medias.create') }}" class="btn btn-success">Créer un média</a>
</div>

<form method="GET" action="{{ route('medias.index') }}" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Rechercher un média..."
               value="{{ request('search') }}">
        <button class="btn btn-primary">Rechercher</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Chemin</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        @forelse($medias as $media)
            <tr>
                <td>{{ $media->id_media }}</td>
                <td>{{ $media->chemin }}</td>
                <td>{{ $media->typeMedia->nom_media ?? '—' }}</td>

                <td class="d-flex gap-2">
                    <a href="{{ route('medias.show', $media->id_media) }}" class="btn btn-info btn-sm">Voir</a>
                    <a href="{{ route('medias.edit', $media->id_media) }}" class="btn btn-warning btn-sm">Éditer</a>

                    <form action="{{ route('medias.destroy', $media->id_media) }}" method="POST"
                          onsubmit="return confirm('Supprimer ce média ?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">Aucun média trouvé</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
