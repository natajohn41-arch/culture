@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Parlers</h1>
    <a href="{{ route('parlers.create') }}" class="btn btn-success">Créer</a>
</div>

<div class="table-responsive">
<table class="table table-striped table-hover align-middle">
    <thead class="table-primary">
        <tr>
            <th>Région</th>
            <th>Langue</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($parlers as $parler)
        <tr>
            <td>{{ $parler->region->nom_region ?? '' }}</td>
            <td>{{ $parler->langue->nom_langue ?? '' }}</td>
            <td class="table-actions">
                <a href="{{ route('parlers.edit', $parler->id) }}" class="btn btn-warning btn-sm">Éditer</a>
                <form action="{{ route('parlers.destroy', $parler->id) }}" method="POST" style="display:inline-block;">
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
