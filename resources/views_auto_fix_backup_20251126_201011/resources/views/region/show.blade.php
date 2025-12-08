@extends('layouts.app')

@section('title', $region->nom_region)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <!-- Carte d'information -->
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-globe-americas display-4 text-primary mb-3"></i>
                    <h3>{{ $region->nom_region }}</h3>
                    
                    <p class="text-muted mt-3">{{ $region->description }}</p>

                    <div class="row mt-4 text-center">
                        @if($region->population)
                            <div class="col-6">
                                <h5 class="text-warning">{{ number_format($region->population, 0, ',', ' ') }}</h5>
                                <small class="text-muted">Habitants</small>
                            </div>
                        @endif
                        @if($region->superficie)
                            <div class="col-6">
                                <h5 class="text-info">{{ number_format($region->superficie, 0, ',', ' ') }} km²</h5>
                                <small class="text-muted">Superficie</small>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <span class="badge bg-primary fs-6">{{ $region->contenus_count }} contenu(s)</span>
                        <span class="badge bg-success fs-6 ms-2">{{ $region->langues_count }} langue(s)</span>
                    </div>
                </div>
            </div>

            <!-- Localisation -->
            @if($region->localisation)
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Localisation</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $region->localisation }}</p>
                    </div>
                </div>
            @endif

            <!-- Langues parlées -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Langues parlées</h6>
                </div>
                <div class="card-body">
                    @if($region->langues->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($region->langues as $langue)
                                <div class="list-group-item px-0 border-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>{{ $langue->nom_langue }}</span>
                                        <code class="text-muted">{{ $langue->code_langue }}</code>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucune langue définie</p>
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
                        <a href="{{ route('region.edit', $region->id_region) }}" 
                           class="btn btn-outline-warning">
                            <i class="bi bi-pencil me-2"></i>Modifier
                        </a>
                        
                        @if($region->contenus_count == 0)
                            <form action="{{ route('region.destroy', $region->id_region) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Supprimer cette région ?')">
                                    <i class="bi bi-trash me-2"></i>Supprimer
                                </button>
                            </form>
                        @else
                            <button class="btn btn-outline-secondary" disabled>
                                <i class="bi bi-trash me-2"></i>Impossible de supprimer
                            </button>
                        @endif

                        <a href="{{ route('region.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Derniers contenus de cette région -->
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Contenus de cette région ({{ $region->contenus_count }})</h6>
                    <a href="{{ route('contenu.index') }}?region={{ $region->id_region }}" 
                       class="btn btn-sm btn-outline-primary">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    @if($region->contenus_count > 0)
                        <div class="list-group list-group-flush">
                            @foreach($region->contenus->take(10) as $contenu)
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
                                                {{ $contenu->langue->nom_langue }} • 
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
                            <p class="text-muted">Aucun contenu n'a été créé pour cette région.</p>
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
                            <td>{{ $region->nom_region }}</td>
                        </tr>
                        <tr>
                            <td><strong>Description :</strong></td>
                            <td>{{ $region->description }}</td>
                        </tr>
                        @if($region->population)
                            <tr>
                                <td><strong>Population :</strong></td>
                                <td>{{ number_format($region->population, 0, ',', ' ') }} habitants</td>
                            </tr>
                        @endif
                        @if($region->superficie)
                            <tr>
                                <td><strong>Superficie :</strong></td>
                                <td>{{ number_format($region->superficie, 0, ',', ' ') }} km²</td>
                            </tr>
                        @endif
                        @if($region->localisation)
                            <tr>
                                <td><strong>Localisation :</strong></td>
                                <td>{{ $region->localisation }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td><strong>Date de création :</strong></td>
                            <td>{{ $region->created_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Dernière modification :</strong></td>
                            <td>{{ $region->updated_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Contenus associés :</strong></td>
                            <td>
                                <span class="badge bg-primary">{{ $region->contenus_count }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Langues parlées :</strong></td>
                            <td>
                                <span class="badge bg-success">{{ $region->langues_count }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection