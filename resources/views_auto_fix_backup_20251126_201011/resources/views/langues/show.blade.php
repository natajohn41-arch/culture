@extends('layouts.app')

@section('title', $langue->nom_langue)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <!-- Carte d'information -->
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-translate display-4 text-primary mb-3"></i>
                    <h3>{{ $langue->nom_langue }}</h3>
                    <h5 class="text-muted mb-3">({{ $langue->code_langue }})</h5>
                    
                    <p class="text-muted">{{ $langue->description }}</p>

                    <div class="mt-4">
                        <span class="badge bg-primary fs-6">{{ $langue->contenus_count }} contenu(s)</span>
                        <span class="badge bg-success fs-6 ms-2">{{ $langue->regions_count }} région(s)</span>
                    </div>
                </div>
            </div>

            <!-- Régions où elle est parlée -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Régions où elle est parlée</h6>
                </div>
                <div class="card-body">
                    @if($langue->regions->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($langue->regions as $region)
                                <div class="list-group-item px-0 border-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('region.show', $region->id_region) }}" 
                                           class="text-decoration-none">
                                            {{ $region->nom_region }}
                                        </a>
                                        <span class="badge bg-light text-dark">{{ $region->contenus_count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucune région définie</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('langues.edit', $langue->id_langue) }}" 
                           class="btn btn-outline-warning">
                            <i class="bi bi-pencil me-2"></i>Modifier
                        </a>
                        
                        @if($langue->contenus_count == 0 && $langue->utilisateurs_count == 0)
                            <form action="{{ route('langues.destroy', $langue->id_langue) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Supprimer cette langue ?')">
                                    <i class="bi bi-trash me-2"></i>Supprimer
                                </button>
                            </form>
                        @else
                            <button class="btn btn-outline-secondary" disabled>
                                <i class="bi bi-trash me-2"></i>Impossible de supprimer
                            </button>
                        @endif

                        <a href="{{ route('langues.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Derniers contenus dans cette langue -->
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Contenus dans cette langue ({{ $langue->contenus_count }})</h6>
                    <a href="{{ route('contenu.index') }}?langue={{ $langue->id_langue }}" 
                       class="btn btn-sm btn-outline-primary">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    @if($langue->contenus_count > 0)
                        <div class="list-group list-group-flush">
                            @foreach($langue->contenus->take(10) as $contenu)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('contenu.show', $contenu->id_contenu) }}" 
                                                   class="text-decoration-none">
                                                    {{ $contenu->titre }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                Par {{ $contenu->auteur->prenom }} • 
                                                {{ $contenu->region->nom_region }} • 
                                                {{ $contenu->typeContenu->nom_contenu }} • 
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
                            <p class="text-muted">Aucun contenu n'a été créé dans cette langue.</p>
                            <a href="{{ route('contenu.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Créer un contenu
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informations détaillées -->
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Informations détaillées</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td width="30%"><strong>Nom :</strong></td>
                            <td>{{ $langue->nom_langue }}</td>
                        </tr>
                        <tr>
                            <td><strong>Code :</strong></td>
                            <td><code>{{ $langue->code_langue }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Description :</strong></td>
                            <td>{{ $langue->description }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date de création :</strong></td>
                            <td>{{ $langue->created_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Dernière modification :</strong></td>
                            <td>{{ $langue->updated_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Contenus associés :</strong></td>
                            <td>
                                <span class="badge bg-primary">{{ $langue->contenus_count }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Régions associées :</strong></td>
                            <td>
                                <span class="badge bg-success">{{ $langue->regions_count }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Utilisateurs :</strong></td>
                            <td>
                                <span class="badge bg-info">{{ $langue->utilisateurs_count ?? 0 }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection