@extends('layout')

@section('Content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Types de Contenus</h1>
    <a href="{{ route('type-contenus.create') }}" class="btn btn-success">Créer</a>
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
    @foreach($typeContenus as $typeContenu)
        <tr>
            <td>{{ $typeContenu->id_type_contenu }}</td>
            <td>{{ $typeContenu->nom_contenu }}</td>
            <td>
                <a href="{{ route('type-contenus.edit', $typeContenu->id_type_contenu) }}"
                   class="btn btn-warning btn-sm">Éditer</a>

                <form action="{{ route('type-contenus.destroy', $typeContenu->id_type_contenu) }}"
                      method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"
                        onclick="return confirm('Supprimer ?')">Supprimer</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
@endsection
