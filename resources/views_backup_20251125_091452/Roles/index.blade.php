@extends('layout')

@section('Content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Roles</h1>
    <a href="{{ route('roles.create') }}" class="btn btn-success">Créer</a>
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
    @foreach($roles as $role)
        <tr>
            <td>{{ $role->id }}</td>
            <td>{{ $role->nom}}</td>
            <td class="table-actions">
                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">Éditer</a>
                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
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
