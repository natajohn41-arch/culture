@extends('layouts.app')

@section('title', 'Gestion des Langues')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3">Gestion des Langues</h1>
                    <p class="text-muted mb-0">Liste de toutes les langues parlées au Bénin</p>
                </div>
                @can('create', App\Models\Langue::class)
                    <a href="{{ route('langues.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Nouvelle Langue
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Total Langues</h6>
                            <h3 class="fw-bold">{{ $langues->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-translate display-6 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Total Contenus</h6>
                            <h3 class="fw-bold">{{ $langues->sum('contenus_count') }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-file-text display-6 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Régions Couvertes</h6>
                            <h3 class="fw-bold">{{ $langues->sum('regions_count') }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-globe display-6 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Utilisateurs</h6>
                            <h3 class="fw-bold">{{ \App\Models\Utilisateur::whereIn('id_langue', $langues->pluck('id_langue'))->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people display-6 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des langues -->
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="mb-0">Langues ({{ $langues->count() }})</h6>
        </div>
        <div class="card-body">
            @if($langues->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Contenus</th>
                                <th>Régions</th>
                                <th>Utilisateurs</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($langues as $langue)
                                <tr>
                                    <td>
                                        <strong>{{ $langue->nom_langue }}</strong>
                                    </td>
                                    <td>
                                        <code>{{ $langue->code_langue }}</code>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ Str::limit($langue->description, 80) }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $langue->contenus_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $langue->regions_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $langue->utilisateurs_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('langues.show', $langue->id_langue) }}" 
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            @can('update', $langue)
                                                <a href="{{ route('langues.edit', $langue->id_langue) }}" 
                                                   class="btn btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan

                                            @can('delete', $langue)
                                                <form action="{{ route('langues.destroy', $langue->id_langue) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger" 
                                                            title="Supprimer"
                                                            onclick="return confirm('Supprimer cette langue ?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
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
                    <i class="bi bi-translate display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">Aucune langue</h4>
                    <p class="text-muted">Aucune langue n'a été créée pour le moment.</p>
                    @can('create', App\Models\Langue::class)
                        <a href="{{ route('langues.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Créer la première langue
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection