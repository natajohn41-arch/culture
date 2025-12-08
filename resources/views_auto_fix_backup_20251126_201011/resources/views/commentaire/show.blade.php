@extends('layouts.app')

@section('title', 'Commentaire')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Détail du Commentaire</h5>
                        <a href="{{ route('contenus.show', $commentaire->id_contenu) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour au contenu
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informations sur le contenu -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="card-title">Commentaire sur :</h6>
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="mb-2">
                                        <a href="{{ route('contenus.show', $commentaire->id_contenu) }}" 
                                           class="text-decoration-none">
                                            {{ $commentaire->contenu->titre }}
                                        </a>
                                    </h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-primary">{{ $commentaire->contenu->region->nom_region }}</span>
                                        <span class="badge bg-success">{{ $commentaire->contenu->langue->nom_langue }}</span>
                                        <span class="badge bg-secondary">{{ $commentaire->contenu->typeContenu->nom_contenu }}</span>
                                        <span class="badge bg-{{ $commentaire->contenu->statut == 'valide' ? 'success' : 'warning' }}">
                                            {{ $commentaire->contenu->statut }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Commentaire -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    @if($commentaire->utilisateur->photo)
                                        <img src="{{ asset('storage/' . $commentaire->utilisateur->photo) }}" 
                                             class="rounded-circle me-3" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3 text-white" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $commentaire->utilisateur->prenom }} {{ $commentaire->utilisateur->nom }}</h6>
                                        <small class="text-muted">
                                            <span class="badge bg-light text-dark">{{ $commentaire->utilisateur->role->nom_role }}</span>
                                            • {{ $commentaire->date->format('d/m/Y à H:i') }}
                                        </small>
                                    </div>
                                </div>
                                
                                @if($commentaire->note)
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $commentaire->note ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                @endif
                            </div>

                            <div class="comment-content border-start border-3 border-primary ps-3 py-2">
                                <p class="mb-0 fs-6">{{ $commentaire->texte }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informations détaillées -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Informations techniques</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td width="30%"><strong>ID du commentaire :</strong></td>
                                    <td>#{{ $commentaire->id_commentaire }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Auteur :</strong></td>
                                    <td>
                                        {{ $commentaire->utilisateur->prenom }} {{ $commentaire->utilisateur->nom }}
                                        <small class="text-muted">({{ $commentaire->utilisateur->email }})</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Contenu :</strong></td>
                                    <td>
                                        <a href="{{ route('contenus.show', $commentaire->id_contenu) }}" class="text-decoration-none">
                                            {{ $commentaire->contenu->titre }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Date de publication :</strong></td>
                                    <td>{{ $commentaire->date->format('d/m/Y à H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Note :</strong></td>
                                    <td>
                                        @if($commentaire->note)
                                            <div class="text-warning d-inline-block">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $commentaire->note ? '-fill' : '' }}"></i>
                                                @endfor
                                                <span class="text-dark ms-2">({{ $commentaire->note }}/5)</span>
                                            </div>
                                        @else
                                            <span class="text-muted">Aucune note</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Longueur :</strong></td>
                                    <td>{{ strlen($commentaire->texte) }} caractères</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card mt-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if(Auth::user()->isAdmin() || Auth::id() == $commentaire->id_utilisateur)
                                    <a href="{{ route('commentaire.edit', $commentaire->id_commentaire) }}" 
                                       class="btn btn-outline-warning">
                                        <i class="bi bi-pencil me-2"></i>Modifier
                                    </a>
                                @endif

                                @if(Auth::user()->isAdmin() || Auth::user()->isModerator() || Auth::id() == $commentaire->id_utilisateur)
                                    <form action="{{ route('commentaire.destroy', $commentaire->id_commentaire) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-outline-danger w-100"
                                                onclick="return confirm('Supprimer ce commentaire ?')">
                                            <i class="bi bi-trash me-2"></i>Supprimer
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('contenus.show', $commentaire->id_contenu) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-file-text me-2"></i>Voir le contenu
                                </a>

                                <a href="{{ route('commentaire.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-list-ul me-2"></i>Tous les commentaires
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.comment-content {
    background-color: #f8f9fa;
    border-radius: 0 8px 8px 0;
}
</style>
@endsection