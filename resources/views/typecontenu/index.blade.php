@extends('layouts.app')

@section('title', 'Types de Contenu')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3">Types de Contenu</h1>
                    <p class="text-muted mb-0">Gestion des catégories de contenu</p>
                </div>
                @can('create', App\Models\TypeContenu::class)
                    <a href="{{ route('type-contenus.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Nouveau Type
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Tableau des types de contenu -->
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="mb-0">Types de Contenu ({{ $typeContenus->count() }})</h6>
        </div>
        <div class="card-body">
            @if($typeContenus->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Contenus</th>
                                <th>Création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($typeContenus as $type)
                                <tr>
                                    <td>
                                        <strong>{{ $type->nom_contenu }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $type->description ?? 'Aucune description' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $type->contenus_count > 0 ? 'primary' : 'secondary' }}">
                                            {{ $type->contenus_count }} contenu(s)
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $type->created_at->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('type-contenus.show', $type->id_type_contenu) }}" 
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            @can('update', $type)
                                                <a href="{{ route('type-contenus.edit', $type->id_type_contenu) }}" 
                                                   class="btn btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan

                                            @can('delete', $type)
                                                @if($type->contenus_count == 0)
                                                    <form action="{{ route('type-contenus.destroy', $type->id_type_contenu) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-outline-danger" 
                                                                title="Supprimer"
                                                                onclick="return confirm('Supprimer ce type de contenu ?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-outline-secondary" 
                                                            title="Impossible de supprimer (utilisé dans des contenus)"
                                                            disabled>
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-tags display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">Aucun type de contenu</h4>
                    <p class="text-muted">Aucun type de contenu n'a été créé pour le moment.</p>
                    @can('create', App\Models\TypeContenu::class)
                        <a href="{{ route('type-contenus.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Créer le premier type
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection