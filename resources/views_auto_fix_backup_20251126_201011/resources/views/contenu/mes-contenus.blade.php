@extends('layouts.app')

@section('title', 'Mes Contenus')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3">Mes Contenus</h1>
                    <p class="text-muted mb-0">Gérez vos contenus créés sur la plateforme</p>
                </div>
                <a href="{{ route('mes-contenus.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Nouveau Contenu
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques personnelles -->
    @php
        $mesContenus = Auth::user()->contenus;
        $contenusValides = $mesContenus->where('statut', 'valide')->count();
        $contenusEnAttente = $mesContenus->where('statut', 'en_attente')->count();
    @endphp

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-file-text display-4 text-primary mb-3"></i>
                    <h3 class="fw-bold">{{ $mesContenus->count() }}</h3>
                    <p class="text-muted mb-0">Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-success">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 text-success mb-3"></i>
                    <h3 class="fw-bold">{{ $contenusValides }}</h3>
                    <p class="text-muted mb-0">Validés</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-clock display-4 text-warning mb-3"></i>
                    <h3 class="fw-bold">{{ $contenusEnAttente }}</h3>
                    <p class="text-muted mb-0">En attente</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des contenus -->
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="mb-0">Mes Contenus ({{ $mesContenus->count() }})</h6>
        </div>
        <div class="card-body">
            @if($mesContenus->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Titre</th>
                                <th>Région</th>
                                <th>Langue</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Commentaires</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mesContenus as $contenu)
                                <tr>
                                    <td>
                                        <a href="{{ route('contenu.show', $contenu->id_contenu) }}" class="text-decoration-none">
                                            {{ Str::limit($contenu->titre, 50) }}
                                        </a>
                                    </td>
                                    <td>{{ $contenu->region->nom_region }}</td>
                                    <td>{{ $contenu->langue->nom_langue }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $contenu->typeContenu->nom_contenu }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $contenu->statut == 'valide' ? 'success' : ($contenu->statut == 'en_attente' ? 'warning' : 'danger') }}">
                                            {{ $contenu->statut }}
                                        </span>
                                        @if($contenu->statut == 'en_attente')
                                            <br>
                                            <small class="text-muted">En attente de validation</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $contenu->date_creation->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $contenu->commentaires->count() }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('contenu.show', $contenu->id_contenu) }}" 
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            <a href="{{ route('mes-contenus.edit', $contenu->id_contenu) }}" 
                                               class="btn btn-outline-warning" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <form action="{{ route('mes-contenus.destroy', $contenu->id_contenu) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger" 
                                                        title="Supprimer"
                                                        onclick="return confirm('Supprimer ce contenu ?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-file-text display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">Aucun contenu créé</h4>
                    <p class="text-muted">Vous n'avez pas encore créé de contenu sur la plateforme.</p>
                    <a href="{{ route('mes-contenus.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Créer votre premier contenu
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection