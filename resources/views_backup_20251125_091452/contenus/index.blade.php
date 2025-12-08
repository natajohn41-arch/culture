@extends('layout')

@section('Content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold m-0"><i class="bi bi-files"></i> Gestion des Contenus</h2>
    <a href="{{ route('contenus.create') }}" class="btn btn-success shadow-sm px-4">
        <i class="bi bi-plus-circle"></i> Nouveau
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Date Création</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($contenus as $contenu)
                    <tr>
                        <td>{{ $contenu->id_contenu }}</td>
                        <td class="fw-semibold">{{ $contenu->titre }}</td>
                        <td>
                            <span class="badge {{ $contenu->statut == 'Bon' ? 'bg-success' : 'bg-danger' }}">
                                {{ $contenu->statut }}
                            </span>
                        </td>
                        <td>{{ $contenu->date_creation }}</td>

                        <td class="text-center">
                            <a href="{{ route('contenus.show', $contenu->id_contenu) }}"
                               class="btn btn-info btn-sm shadow-sm me-1">
                                Voir
                            </a>

                            <a href="{{ route('contenus.edit', $contenu->id_contenu) }}"
                               class="btn btn-warning btn-sm shadow-sm me-1">
                                Éditer
                            </a>

                            <form action="{{ route('contenus.destroy', $contenu->id_contenu) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm shadow-sm"
                                        onclick="return confirm('Supprimer ce contenu ?')">
                                    Supprimer
                                </button>
                            </form>
                        </td>

                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection
