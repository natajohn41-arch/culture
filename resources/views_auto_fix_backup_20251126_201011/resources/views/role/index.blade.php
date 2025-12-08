@extends('layouts.app')

@section('title', 'Gestion des Rôles')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3">Gestion des Rôles</h1>
                    <p class="text-muted mb-0">Configuration des rôles et permissions des utilisateurs</p>
                </div>
                @can('create', App\Models\Role::class)
                    <a href="{{ route('role.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Nouveau Rôle
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Tableau des rôles -->
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="mb-0">Rôles ({{ $roles->count() }})</h6>
        </div>
        <div class="card-body">
            @if($roles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nom du Rôle</th>
                                <th>Description</th>
                                <th>Utilisateurs</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>
                                        <strong>{{ $role->nom_role }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $role->description ?? 'Aucune description' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $role->utilisateurs_count > 0 ? 'primary' : 'secondary' }}">
                                            {{ $role->utilisateurs_count }} utilisateur(s)
                                        </span>
                                    </td>
                                    <td>
                                        @if(in_array($role->nom_role, ['Admin', 'Moderateur', 'Auteur', 'Utilisateur']))
                                            <span class="badge bg-success">Système</span>
                                        @else
                                            <span class="badge bg-info">Personnalisé</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('role.show', $role->id_role) }}" 
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            @can('update', $role)
                                                <a href="{{ route('role.edit', $role->id_role) }}" 
                                                   class="btn btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan

                                            @can('delete', $role)
                                                @if(!in_array($role->nom_role, ['Admin', 'Moderateur', 'Auteur', 'Utilisateur']))
                                                    <form action="{{ route('role.destroy', $role->id_role) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-outline-danger" 
                                                                title="Supprimer"
                                                                onclick="return confirm('Supprimer ce rôle ?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Légende des permissions -->
                <div class="mt-4">
                    <h6>Permissions par défaut :</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">Admin</h6>
                                    <small class="text-muted">Accès complet à toutes les fonctionnalités</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h6 class="card-title text-warning">Modérateur</h6>
                                    <small class="text-muted">Validation des contenus et commentaires</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h6 class="card-title text-success">Auteur</h6>
                                    <small class="text-muted">Création et gestion de ses contenus</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-secondary">
                                <div class="card-body">
                                    <h6 class="card-title text-secondary">Utilisateur</h6>
                                    <small class="text-muted">Consultation et commentaires</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-person-badge display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">Aucun rôle</h4>
                    <p class="text-muted">Aucun rôle n'a été créé pour le moment.</p>
                    @can('create', App\Models\Role::class)
                        <a href="{{ route('role.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Créer le premier rôle
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection