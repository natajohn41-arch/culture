@extends('layouts.app')

@section('title', 'Gestion des Contenus')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3">Gestion des Contenus</h1>
                    <p class="text-muted mb-0">Liste de tous les contenus de la plateforme</p>
                    @can('create', App\Models\Contenu::class)
                        <a href="{{ route('contenus.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Nouveau Contenu
                        </a>
                    @endcan
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('contenus.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="statut" class="form-label">Statut</label>
                            <select name="statut" id="statut" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="rejete" {{ request('statut') == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="region" class="form-label">Région</label>
                            <select name="region" id="region" class="form-select">
                                <option value="">Toutes les régions</option>
                                @foreach($regions = \App\Models\Region::all() as $region)
                                    <option value="{{ $region->id_region }}" {{ request('region') == $region->id_region ? 'selected' : '' }}>
                                        {{ $region->nom_region }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="auteur" class="form-label">Auteur</label>
                            <select name="auteur" id="auteur" class="form-select">
                                <option value="">Tous les auteurs</option>
                                @foreach($auteurs = \App\Models\Utilisateur::whereHas('role', function($q) { $q->whereIn('nom_role', ['Auteur', 'Admin']); })->get() as $auteur)
                                    <option value="{{ $auteur->id_utilisateur }}" {{ request('auteur') == $auteur->id_utilisateur ? 'selected' : '' }}>
                                        {{ $auteur->prenom }} {{ $auteur->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-funnel me-2"></i>Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des contenus -->
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="mb-0">Contenus ({{ $contenus->count() }})</h6>
        </div>
        <div class="card-body">
            @if($contenus->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Auteur</th>
                                <th>Région</th>
                                <th>Langue</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contenus as $contenu)
                                <tr>
                                    <td>{{ $contenu->id_contenu }}</td>
                                    <td>
                                        <a href="{{ route('contenus.show.public', $contenu->id_contenu) }}" class="text-decoration-none">
                                            {{ Str::limit($contenu->titre, 40) }}
                                        </a>
                                        @if($contenu->commentaires_count > 0)
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-chat-text"></i> {{ $contenu->commentaires_count }} commentaire(s)
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($contenu->auteur->photo)
                                                <img src="{{ asset('storage/' . $contenu->auteur->photo) }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2 text-white" 
                                                     style="width: 32px; height: 32px; font-size: 12px;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $contenu->auteur->prenom }}</div>
                                                <small class="text-muted">{{ $contenu->auteur->role->nom_role }}</small>
                                            </div>
                                        </div>
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
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $contenu->date_creation->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                                          <a href="{{ route('contenus.show.public', $contenu->id_contenu) }}" 
                                                              class="btn btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            @if(Auth::user()->isAdmin() || Auth::id() == $contenu->id_auteur)
                                                    <a href="{{ route('contenus.edit', $contenu->id_contenu) }}" 
                                                   class="btn btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif

                                            @if(Auth::user()->isModerator() || Auth::user()->isAdmin())
                                                @if($contenu->statut == 'en_attente')
                                                    <form action="{{ route('contenus.valider', $contenu->id_contenu) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success" title="Valider">
                                                            <i class="bi bi-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif

                                            @if(Auth::user()->isAdmin() || Auth::user()->isModerator() || Auth::id() == $contenu->id_auteur)
                                                <form action="{{ route('contenus.destroy', $contenu->id_contenu) }}" 
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
                                            @endif
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
                    <h4 class="text-muted mt-3">Aucun contenu</h4>
                    <p class="text-muted">Aucun contenu n'a été créé pour le moment.</p>
                    @can('create', App\Models\Contenu::class)
                        <a href="{{ route('contenus.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Créer le premier contenu
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection