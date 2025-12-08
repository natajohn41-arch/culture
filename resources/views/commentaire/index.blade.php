@extends('layouts.app')

@section('title', 'Gestion des Commentaires')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3">Gestion des Commentaires</h1>
                    <p class="text-muted mb-0">Modération des commentaires des utilisateurs</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('commentaires.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="contenu" class="form-label">Contenu</label>
                            <select name="contenu" id="contenu" class="form-select">
                                <option value="">Tous les contenus</option>
                                @foreach($contenus = \App\Models\Contenu::where('statut', 'valide')->get() as $contenu)
                                    <option value="{{ $contenu->id_contenu }}" {{ request('contenu') == $contenu->id_contenu ? 'selected' : '' }}>
                                        {{ Str::limit($contenu->titre, 40) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="utilisateur" class="form-label">Utilisateur</label>
                            <select name="utilisateur" id="utilisateur" class="form-select">
                                <option value="">Tous les utilisateurs</option>
                                @foreach($utilisateurs = \App\Models\Utilisateur::all() as $user)
                                    <option value="{{ $user->id_utilisateur }}" {{ request('utilisateur') == $user->id_utilisateur ? 'selected' : '' }}>
                                        {{ $user->prenom }} {{ $user->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-funnel me-2"></i>Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des commentaires -->
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="mb-0">Commentaires ({{ $commentaires->count() }})</h6>
        </div>
        <div class="card-body">
            @if($commentaires->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Contenu</th>
                                <th>Utilisateur</th>
                                <th>Commentaire</th>
                                <th>Note</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commentaires as $commentaire)
                                <tr>
                                    <td>
                                        <a href="{{ route('contenus.show', $commentaire->id_contenu) }}" 
                                           class="text-decoration-none">
                                            {{ Str::limit($commentaire->contenu->titre, 40) }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($commentaire->utilisateur->photo)
                                                <img src="{{ asset('storage/' . $commentaire->utilisateur->photo) }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2 text-white" 
                                                     style="width: 32px; height: 32px; font-size: 12px;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $commentaire->utilisateur->prenom }}</div>
                                                <small class="text-muted">{{ $commentaire->utilisateur->role->nom_role }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="comment-text">
                                            {{ Str::limit($commentaire->texte, 80) }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($commentaire->note)
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $commentaire->note ? '-fill' : '' }}"></i>
                                                @endfor
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $commentaire->date->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('commentaires.show', $commentaire->id_commentaire) }}" 
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            @if(Auth::user()->isAdmin() || Auth::id() == $commentaire->id_utilisateur)
                                                <a href="{{ route('commentaires.edit', $commentaire->id_commentaire) }}" 
                                                   class="btn btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif

                                            @if(Auth::user()->isAdmin() || Auth::user()->isModerator() || Auth::id() == $commentaire->id_utilisateur)
                                                <form action="{{ route('commentaires.destroy', $commentaire->id_commentaire) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger" 
                                                            title="Supprimer"
                                                            onclick="return confirm('Supprimer ce commentaire ?')">
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
                    <i class="bi bi-chat-text display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">Aucun commentaire</h4>
                    <p class="text-muted">Aucun commentaire n'a été publié pour le moment.</p>
                    <a href="{{ route('contenus.public') }}" class="btn btn-primary">
                        <i class="bi bi-eye me-2"></i>Voir les contenus
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.comment-text {
    max-width: 200px;
    word-wrap: break-word;
}
</style>
@endsection