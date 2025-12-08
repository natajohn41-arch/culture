@extends('layouts.app')

@section('title', $typeContenu->nom_contenu)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <!-- Carte d'information -->
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-tags display-4 text-primary mb-3"></i>
                    <h3>{{ $typeContenu->nom_contenu }}</h3>
                    
                    @if($typeContenu->description)
                        <p class="text-muted mt-3">{{ $typeContenu->description }}</p>
                    @endif

                    <div class="mt-4">
                        <span class="badge bg-primary fs-6">{{ $typeContenu->contenus_count }} contenu(s)</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('typecontenu.edit', $typeContenu->id_type_contenu) }}" 
                           class="btn btn-outline-warning">
                            <i class="bi bi-pencil me-2"></i>Modifier
                        </a>
                        
                        @if($typeContenu->contenus_count == 0)
                            <form action="{{ route('typecontenu.destroy', $typeContenu->id_type_contenu) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Supprimer ce type de contenu ?')">
                                    <i class="bi bi-trash me-2"></i>Supprimer
                                </button>
                            </form>
                        @else
                            <button class="btn btn-outline-secondary" disabled>
                                <i class="bi bi-trash me-2"></i>Impossible de supprimer
                            </button>
                        @endif

                        <a href="{{ route('typecontenu.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Derniers contenus de ce type -->
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Contenus de ce type ({{ $typeContenu->contenus_count }})</h6>
                    <a href="{{ route('contenus.index') }}?type={{ $typeContenu->id_type_contenu }}" 
                       class="btn btn-sm btn-outline-primary">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    @if($typeContenu->contenus_count > 0)
                        <div class="list-group list-group-flush">
                            @foreach($typeContenu->contenus->take(10) as $contenu)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('contenus.show', $contenu->id_contenu) }}" 
                                                   class="text-decoration-none">
                                                    {{ $contenu->titre }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                Par {{ $contenu->auteur->prenom }} • 
                                                {{ $contenu->region->nom_region }} • 
                                                {{ $contenu->date_creation->format('d/m/Y') }}
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ $contenu->statut == 'valide' ? 'success' : 'warning' }}">
                                            {{ $contenu->statut }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-file-text display-4 text-muted"></i>
                            <h5 class="text-muted mt-3">Aucun contenu</h5>
                            <p class="text-muted">Aucun contenu n'utilise encore ce type.</p>
                            <a href="{{ route('contenus.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Créer un contenu
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informations détaillées -->
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Informations</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td width="30%"><strong>Nom :</strong></td>
                            <td>{{ $typeContenu->nom_contenu }}</td>
                        </tr>
                        <tr>
                            <td><strong>Description :</strong></td>
                            <td>{{ $typeContenu->description ?? 'Aucune description' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date de création :</strong></td>
                            <td>{{ $typeContenu->created_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Dernière modification :</strong></td>
                            <td>{{ $typeContenu->updated_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Contenus associés :</strong></td>
                            <td>
                                <span class="badge bg-primary">{{ $typeContenu->contenus_count }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection