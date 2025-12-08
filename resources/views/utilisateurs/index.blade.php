@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3">Gestion des Utilisateurs</h1>
                    <p class="text-muted mb-0">Liste de tous les utilisateurs de la plateforme</p>
                </div>
                @can('create', App\Models\Utilisateur::class)
                    <a href="{{ route('utilisateurs.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Nouvel Utilisateur
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
                            <h6 class="text-muted mb-2">Total</h6>
                            <h3 class="fw-bold">{{ $utilisateurs->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people display-6 text-primary"></i>
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
                            <h6 class="text-muted mb-2">Actifs</h6>
                            <h3 class="fw-bold">{{ $utilisateurs->where('statut', 'actif')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle display-6 text-success"></i>
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
                            <h6 class="text-muted mb-2">Auteurs</h6>
                            <h3 class="fw-bold">{{ $utilisateurs->where('role.nom_role', 'Auteur')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-pencil display-6 text-warning"></i>
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
                            <h6 class="text-muted mb-2">Modérateurs</h6>
                            <h3 class="fw-bold">{{ $utilisateurs->where('role.nom_role', 'Moderateur')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-eye display-6 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des utilisateurs -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Utilisateurs ({{ $utilisateurs->count() }})</h6>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-1"></i>Exporter
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">CSV</a></li>
                    <li><a class="dropdown-item" href="#">Excel</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            @if($utilisateurs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Utilisateur</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Langue</th>
                                <th>Statut</th>
                                <th>Inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($utilisateurs as $user)
                                <tr>
                                    <td>{{ $user->id_utilisateur }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($user->photo)
                                                <img src="{{ asset('storage/' . $user->photo) }}" 
                                                     class="rounded-circle me-3" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3 text-white" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $user->prenom }} {{ $user->nom }}</div>
                                                <small class="text-muted">
                                                    {{ $user->sexe == 'M' ? 'Homme' : 'Femme' }} • 
                                                    {{ $user->date_naissance->age }} ans
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role->nom_role == 'Admin' ? 'danger' : ($user->role->nom_role == 'Moderateur' ? 'warning' : ($user->role->nom_role == 'Auteur' ? 'success' : 'secondary')) }}">
                                            {{ $user->role->nom_role }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $user->langue->nom_langue }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->statut == 'actif' ? 'success' : 'secondary' }}">
                                            {{ $user->statut }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $user->date_inscription->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('utilisateurs.show', $user->id_utilisateur) }}" 
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            <a href="{{ route('utilisateurs.edit', $user->id_utilisateur) }}" 
                                               class="btn btn-outline-warning" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <!-- Activation/Désactivation -->
                                            @if(Auth::id() !== $user->id_utilisateur)
                                                <form action="{{ route('utilisateurs.toggle-status', $user->id_utilisateur) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-outline-{{ $user->statut == 'actif' ? 'warning' : 'success' }}" 
                                                            title="{{ $user->statut == 'actif' ? 'Désactiver' : 'Activer' }}">
                                                        <i class="bi bi-{{ $user->statut == 'actif' ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Suppression -->
                                            @if(Auth::id() !== $user->id_utilisateur)
                                                <form action="{{ route('utilisateurs.destroy', $user->id_utilisateur) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger" 
                                                            title="Supprimer"
                                                            onclick="return confirm('Supprimer cet utilisateur ? Cette action est irréversible.')">
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
                    <i class="bi bi-people display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">Aucun utilisateur</h4>
                    <p class="text-muted">Aucun utilisateur n'est inscrit sur la plateforme.</p>
                    <a href="{{ route('utilisateurs.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Créer le premier utilisateur
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection