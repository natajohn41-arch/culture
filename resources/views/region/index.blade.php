@extends('layouts.app')

@section('title', 'Gestion des Régions')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3">Gestion des Régions</h1>
                    <p class="text-muted mb-0">Liste de toutes les régions du Bénin</p>
                </div>
                @can('create', App\Models\Region::class)
                    <a href="{{ route('regions.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Nouvelle Région
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
                            <h6 class="text-muted mb-2">Total Régions</h6>
                            <h3 class="fw-bold">{{ $regions->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-globe-americas display-6 text-primary"></i>
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
                            <h3 class="fw-bold">{{ $regions->sum('contenus_count') }}</h3>
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
                            <h6 class="text-muted mb-2">Langues Parlées</h6>
                            <h3 class="fw-bold">{{ \App\Models\Parler::distinct('id_langue')->count('id_langue') }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-translate display-6 text-warning"></i>
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
                            <h6 class="text-muted mb-2">Moyenne/Region</h6>
                            <h3 class="fw-bold">{{ round($regions->avg('contenus_count'), 1) }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-graph-up display-6 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des régions -->
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="mb-0">Régions ({{ $regions->count() }})</h6>
        </div>
        <div class="card-body">
            @if($regions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Population</th>
                                <th>Superficie</th>
                                <th>Localisation</th>
                                <th>Contenus</th>
                                <th>Langues</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($regions as $region)
                                <tr>
                                    <td>
                                        <strong>{{ $region->nom_region }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ Str::limit($region->description, 80) }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($region->population)
                                            {{ number_format($region->population, 0, ',', ' ') }} hab.
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($region->superficie)
                                            {{ number_format($region->superficie, 0, ',', ' ') }} km²
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ Str::limit($region->localisation, 40) }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $region->contenus_count }}</span>
                                    </td>
                                    <td>
                                        @if($region->langues->count() > 0)
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                        type="button" data-bs-toggle="dropdown">
                                                    {{ $region->langues->count() }} langue(s)
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @foreach($region->langues as $langue)
                                                        <li><a class="dropdown-item small" href="#">{{ $langue->nom_langue }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <span class="badge bg-secondary">Aucune</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                                          <a href="{{ route('regions.show', $region->id_region) }}" 
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            @can('update', $region)
                                                                                                <a href="{{ route('regions.edit', $region->id_region) }}" 
                                                   class="btn btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan

                                            @can('delete', $region)
                                                <form action="{{ route('regions.destroy', $region->id_region) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger" 
                                                            title="Supprimer"
                                                            onclick="return confirm('Supprimer cette région ?')">
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
                    <i class="bi bi-globe display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">Aucune région</h4>
                    <p class="text-muted">Aucune région n'a été créée pour le moment.</p>
                    @can('create', App\Models\Region::class)
                        <a href="{{ route('regions.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Créer la première région
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection