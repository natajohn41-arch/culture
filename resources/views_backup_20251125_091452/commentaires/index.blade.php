@extends('layout')

@section('Content')

<div class="d-flex justify-content-between align-items-center mb-3 mt-3">
    <h1 class="fw-bold">Commentaires</h1>
    <a href="{{ route('commentaires.create') }}" class="btn btn-success px-4">Créer</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Texte</th>
                    <th>Note</th>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Contenu</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            @foreach($commentaires as $commentaire)
                <tr>
                    <td>{{ $commentaire->id_commentaire }}</td>
                    <td>{{ Str::limit($commentaire->texte, 50) }}</td>

                    {{-- Affichage étoilé --}}
                    <td>
                        @for ($i=1; $i <= 5; $i++)
                            <span class="{{ $i <= $commentaire->note ? 'text-warning' : 'text-secondary' }}">★</span>
                        @endfor
                    </td>

                    <td>{{ $commentaire->date }}</td>
                    <td>{{ $commentaire->utilisateur->nom ?? '' }}</td>
                    <td>{{ $commentaire->contenu->titre ?? '' }}</td>

                    <td>
                        <a href="{{ route('commentaires.show', $commentaire->id_commentaire) }}" class="btn btn-info btn-sm">Voir</a>
                        <a href="{{ route('commentaires.edit', $commentaire->id_commentaire) }}" class="btn btn-warning btn-sm">Éditer</a>

                        <form action="{{ route('commentaires.destroy', $commentaire->id_commentaire) }}" method="POST" style="display:inline-block;">
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
</div>

@endsection
