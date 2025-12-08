@extends('layouts.app')

@section('title', 'Contenus - Culture CMS')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="bi bi-file-text me-2"></i>Contenus</h1>
    </div>
    <a href="{{ route('contenu.create') }}" class="btn btn-culture">
        <i class="bi bi-plus-lg me-2"></i>Ajouter un contenu
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <i class="bi bi-list me-2"></i>Liste des contenus
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="contenus-table" class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Région</th>
                        <th>Langue</th>
                        <th>Statut</th>
                        <th>Auteur</th>
                        <th>Date création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contenus as $contenu)
                        <tr>
                            <td><strong>{{ $contenu->titre ?? '-' }}</strong></td>
                            <td><span class="badge bg-info">{{ $contenu->id_region ?? '-' }}</span></td>
                            <td><span class="badge bg-secondary">{{ $contenu->id_langue ?? '-' }}</span></td>
                            <td>
                                @if($contenu->statut == 'publié')
                                    <span class="badge bg-success">Publié</span>
                                @elseif($contenu->statut == 'brouillon')
                                    <span class="badge bg-warning">Brouillon</span>
                                @else
                                    <span class="badge bg-secondary">{{ $contenu->statut ?? '-' }}</span>
                                @endif
                            </td>
                            <td>{{ $contenu->id_auteur ?? '-' }}</td>
                            <td>{{ $contenu->date_creation ? \Carbon\Carbon::parse($contenu->date_creation)->format('d/m/Y') : '-' }}</td>
                            <td>
                                <a href="{{ route('contenu.show', $contenu->id_contenu) }}" class="btn btn-sm btn-info" title="Voir"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('contenu.edit', $contenu->id_contenu) }}" class="btn btn-sm btn-warning" title="Éditer"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('contenu.destroy', $contenu->id_contenu) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce contenu ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3"></i><br>
                                Aucun contenu trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#contenus-table').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' },
        responsive: true,
        pageLength: 15
    });
});
</script>
@endsection