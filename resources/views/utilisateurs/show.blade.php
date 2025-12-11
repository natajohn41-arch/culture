@extends('layouts.app')

@section('title', $utilisateur->prenom . ' ' . $utilisateur->nom)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <!-- Carte profil -->
            <div class="card">
                <div class="card-body text-center">
                    @if($utilisateur->hasPhoto())
                        <img src="{{ $utilisateur->photo_url }}" 
                             class="rounded-circle mb-3" 
                             style="width: 150px; height: 150px; object-fit: cover;"
                             alt="Photo de profil">
                    @else
                        <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3 text-white" 
                             style="width: 150px; height: 150px;">
                            <i class="bi bi-person-fill" style="font-size: 3rem;"></i>
                        </div>
                    @endif

                    <h4>{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</h4>
                    <span class="badge bg-{{ $utilisateur->role->nom_role == 'Admin' ? 'danger' : ($utilisateur->role->nom_role == 'Moderateur' ? 'warning' : ($utilisateur->role->nom_role == 'Auteur' ? 'success' : 'secondary')) }} fs-6">
                        {{ $utilisateur->role->nom_role }}
                    </span>

                    <div class="mt-3">
                        <span class="badge bg-{{ $utilisateur->statut == 'actif' ? 'success' : 'secondary' }}">
                            {{ $utilisateur->statut }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informations de contact -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Informations de contact</h6>
                </div>
                <div class="card-body">
                    <p>
                        <i class="bi bi-envelope me-2 text-primary"></i>
                        <strong>Email :</strong><br>
                        {{ $utilisateur->email }}
                    </p>
                    <p>
                        <i class="bi bi-translate me-2 text-success"></i>
                        <strong>Langue :</strong><br>
                        {{ $utilisateur->langue->nom_langue }}
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(Auth::user()->isAdmin() || Auth::id() == $utilisateur->id_utilisateur)
                            <a href="{{ route('utilisateurs.edit', $utilisateur->id_utilisateur) }}" 
                               class="btn btn-outline-warning">
                                <i class="bi bi-pencil me-2"></i>Modifier
                            </a>
                        @endif

                        @if(Auth::user()->isAdmin() && Auth::id() !== $utilisateur->id_utilisateur)
                            <form action="{{ route('utilisateurs.toggle-status', $utilisateur->id_utilisateur) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-outline-{{ $utilisateur->statut == 'actif' ? 'warning' : 'success' }} w-100">
                                    <i class="bi bi-{{ $utilisateur->statut == 'actif' ? 'pause' : 'play' }} me-2"></i>
                                    {{ $utilisateur->statut == 'actif' ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>

                            <form action="{{ route('utilisateurs.destroy', $utilisateur->id_utilisateur) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Supprimer définitivement cet utilisateur ?')">
                                    <i class="bi bi-trash me-2"></i>Supprimer
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('utilisateurs.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Informations détaillées -->
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Informations détaillées</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informations personnelles</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Nom complet :</strong></td>
                                    <td>{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Sexe :</strong></td>
                                    <td>{{ $utilisateur->sexe == 'M' ? 'Homme' : 'Femme' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date de naissance :</strong></td>
                                    <td>
                                        @if($utilisateur->date_naissance)
                                            {{ $utilisateur->date_naissance->format('d/m/Y') }} ({{ $utilisateur->age }} ans)
                                        @else
                                            <span class="text-muted">Non renseignée</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Rôle :</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $utilisateur->role->nom_role == 'Admin' ? 'danger' : ($utilisateur->role->nom_role == 'Moderateur' ? 'warning' : ($utilisateur->role->nom_role == 'Auteur' ? 'success' : 'secondary')) }}">
                                            {{ $utilisateur->role->nom_role }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6>Informations du compte</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Statut :</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $utilisateur->statut == 'actif' ? 'success' : 'secondary' }}">
                                            {{ $utilisateur->statut }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Date d'inscription :</strong></td>
                                    <td>{{ $utilisateur->date_inscription->format('d/m/Y à H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dernière modification :</strong></td>
                                    <td>{{ $utilisateur->updated_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Langue préférée :</strong></td>
                                    <td>{{ $utilisateur->langue->nom_langue }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Statistiques</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <i class="bi bi-file-text display-6 text-primary mb-2"></i>
                                <h4>{{ $utilisateur->contenus->count() }}</h4>
                                <small class="text-muted">Contenus créés</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <i class="bi bi-chat-text display-6 text-success mb-2"></i>
                                <h4>{{ $utilisateur->commentaires->count() }}</h4>
                                <small class="text-muted">Commentaires</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <i class="bi bi-clock display-6 text-warning mb-2"></i>
                                <h4>{{ $utilisateur->contenus->where('statut', 'en_attente')->count() }}</h4>
                                <small class="text-muted">En attente</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Derniers contenus (pour les auteurs) -->
            @if($utilisateur->contenus->count() > 0)
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Derniers contenus</h6>
                        <a href="{{ route('contenus.index') }}?auteur={{ $utilisateur->id_utilisateur }}" 
                           class="btn btn-sm btn-outline-primary">
                            Voir tout
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($utilisateur->contenus->take(5) as $contenu)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('contenus.show.public', $contenu->id_contenu) }}" 
                                                   class="text-decoration-none">
                                                    {{ Str::limit($contenu->titre, 50) }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                {{ $contenu->region->nom_region }} • 
                                                {{ $contenu->date_creation->format('d/m/Y') }}
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ $contenu->statut == 'valide' ? 'success' : ($contenu->statut == 'en_attente' ? 'warning' : 'danger') }}">
                                            {{ $contenu->statut }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection