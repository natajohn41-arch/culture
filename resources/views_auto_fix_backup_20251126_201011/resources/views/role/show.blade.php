@extends('layouts.app')

@section('title', $role->nom_role)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <!-- Carte d'information -->
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-person-badge display-4 text-primary mb-3"></i>
                    <h3>{{ $role->nom_role }}</h3>
                    
                    @if($role->description)
                        <p class="text-muted mt-3">{{ $role->description }}</p>
                    @endif

                    <div class="mt-4">
                        <span class="badge bg-primary fs-6">{{ $role->utilisateurs_count }} utilisateur(s)</span>
                    </div>

                    @if(in_array($role->nom_role, ['Admin', 'Moderateur', 'Auteur', 'Utilisateur']))
                        <div class="mt-2">
                            <span class="badge bg-success">Rôle système</span>
                        </div>
                    @else
                        <div class="mt-2">
                            <span class="badge bg-info">Rôle personnalisé</span>
                        </div>
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
                        <a href="{{ route('role.edit', $role->id_role) }}" 
                           class="btn btn-outline-warning">
                            <i class="bi bi-pencil me-2"></i>Modifier
                        </a>
                        
                        @if(!in_array($role->nom_role, ['Admin', 'Moderateur', 'Auteur', 'Utilisateur']) && $role->utilisateurs_count == 0)
                            <form action="{{ route('role.destroy', $role->id_role) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Supprimer ce rôle ?')">
                                    <i class="bi bi-trash me-2"></i>Supprimer
                                </button>
                            </form>
                        @else
                            <button class="btn btn-outline-secondary" disabled>
                                <i class="bi bi-trash me-2"></i>Impossible de supprimer
                            </button>
                        @endif

                        <a href="{{ route('role.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Utilisateurs avec ce rôle -->
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Utilisateurs avec ce rôle ({{ $role->utilisateurs_count }})</h6>
                    <a href="{{ route('utilisateurs.index') }}?role={{ $role->id_role }}" 
                       class="btn btn-sm btn-outline-primary">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    @if($role->utilisateurs_count > 0)
                        <div class="list-group list-group-flush">
                            @foreach($role->utilisateurs->take(10) as $user)
                                <div class="list-group-item px-0">
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
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $user->prenom }} {{ $user->nom }}</h6>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                        <span class="badge bg-{{ $user->statut == 'actif' ? 'success' : 'secondary' }}">
                                            {{ $user->statut }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-people display-4 text-muted"></i>
                            <h5 class="text-muted mt-3">Aucun utilisateur</h5>
                            <p class="text-muted">Aucun utilisateur n'a ce rôle pour le moment.</p>
                            <a href="{{ route('utilisateurs.create') }}" class="btn btn-primary">
                                <i class="bi bi-person-plus me-2"></i>Créer un utilisateur
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
                            <td>{{ $role->nom_role }}</td>
                        </tr>
                        <tr>
                            <td><strong>Description :</strong></td>
                            <td>{{ $role->description ?? 'Aucune description' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Type :</strong></td>
                            <td>
                                @if(in_array($role->nom_role, ['Admin', 'Moderateur', 'Auteur', 'Utilisateur']))
                                    <span class="badge bg-success">Système</span>
                                @else
                                    <span class="badge bg-info">Personnalisé</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Date de création :</strong></td>
                            <td>{{ $role->created_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Dernière modification :</strong></td>
                            <td>{{ $role->updated_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Utilisateurs associés :</strong></td>
                            <td>
                                <span class="badge bg-primary">{{ $role->utilisateurs_count }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection