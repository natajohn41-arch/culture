@extends('layouts.app')

@section('title', 'Détails du Contenu - ' . ($contenu->titre ?? 'Sans titre'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <!-- Carte d'information -->
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-file-text display-4 text-primary mb-3"></i>
                    <h3>{{ $contenu->titre ?? 'Sans titre' }}</h3>
                    
                    @php
                        $statusColors = [
                            'en_attente' => 'warning',
                            'valide' => 'success',
                            'rejete' => 'danger'
                        ];
                        $statusLabels = [
                            'en_attente' => 'En attente',
                            'valide' => 'Validé',
                            'rejete' => 'Rejeté'
                        ];
                    @endphp
                    <div class="mt-3">
                        <span class="badge bg-{{ $statusColors[$contenu->statut] ?? 'secondary' }} fs-6">
                            {{ $statusLabels[$contenu->statut] ?? $contenu->statut }}
                        </span>
                    </div>

                    @if($contenu->auteur)
                        <div class="mt-3">
                            <p class="text-muted mb-1">Auteur</p>
                            <h6>{{ $contenu->auteur->prenom }} {{ $contenu->auteur->nom }}</h6>
                            <small class="text-muted">{{ $contenu->auteur->role->nom_role ?? 'Membre' }}</small>
                        </div>
                    @endif

                    <div class="mt-4">
                        <span class="badge bg-primary fs-6">{{ $contenu->medias_count ?? $contenu->medias->count() }} média(s)</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                            <a href="{{ route('contenus.edit', $contenu->id_contenu) }}" 
                               class="btn btn-outline-warning">
                                <i class="bi bi-pencil me-2"></i>Modifier
                            </a>
                            
                            <form action="{{ route('contenus.destroy', $contenu->id_contenu) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Supprimer ce contenu ? Cette action est irréversible.')">
                                    <i class="bi bi-trash me-2"></i>Supprimer
                                </button>
                            </form>
                            
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('contenus.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                                </a>
                            @else
                                <a href="{{ route('contenus.a-valider') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Retour aux contenus à valider
                                </a>
                            @endif
                        @elseif(Auth::id() == $contenu->id_auteur)
                            <a href="{{ route('mes.contenus.edit', $contenu->id_contenu) }}" 
                               class="btn btn-outline-warning">
                                <i class="bi bi-pencil me-2"></i>Modifier
                            </a>
                            
                            <form action="{{ route('mes.contenus.destroy', $contenu->id_contenu) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Supprimer ce contenu ? Cette action est irréversible.')">
                                    <i class="bi bi-trash me-2"></i>Supprimer
                                </button>
                            </form>
                            
                            <a href="{{ route('mes.contenus.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Retour à mes contenus
                            </a>
                        @else
                            <!-- Pour les visiteurs non-auteurs -->
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('contenus.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                                </a>
                            @else
                                <a href="{{ route('mes.contenus.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Retour à mes contenus
                                </a>
                            @endif
                        @endif
                        
                        <!-- Actions de modération pour admin/moderator -->
                        @if((Auth::user()->isAdmin() || Auth::user()->isModerator()) && $contenu->statut == 'en_attente')
                            <div class="btn-group w-100 mt-2">
                                <form action="{{ route('contenus.valider', $contenu->id_contenu) }}" method="POST" class="flex-fill">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100" 
                                            onclick="return confirm('Valider ce contenu ?')">
                                        <i class="bi bi-check-circle me-2"></i>Valider
                                    </button>
                                </form>
                                <form action="{{ route('contenus.rejeter', $contenu->id_contenu) }}" method="POST" class="flex-fill">
                                    @csrf
                                    <button type="submit" class="btn btn-warning w-100"
                                            onclick="return confirm('Rejeter ce contenu ?')">
                                        <i class="bi bi-x-circle me-2"></i>Rejeter
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Informations rapides -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Informations rapides</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td width="40%"><strong>ID :</strong></td>
                            <td>{{ $contenu->id_contenu }}</td>
                        </tr>
                        <tr>
                            <td><strong>Type :</strong></td>
                            <td>{{ $contenu->typeContenu->nom_contenu ?? 'Non défini' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Région :</strong></td>
                            <td>{{ $contenu->region->nom_region ?? 'Non définie' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Langue :</strong></td>
                            <td>{{ $contenu->langue->nom_langue ?? 'Non définie' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Créé le :</strong></td>
                            <td>{{ $contenu->date_creation ? $contenu->date_creation->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                        @if($contenu->date_validation)
                            <tr>
                                <td><strong>Validé le :</strong></td>
                                <td>{{ $contenu->date_validation->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endif
                        @if($contenu->parent_id)
                            <tr>
                                <td><strong>Parent :</strong></td>
                                <td>
                                    ID: {{ $contenu->parent_id }}
                                    @if($contenu->parent)
                                        - {{ $contenu->parent->titre }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Contenu détaillé -->
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Contenu</h6>
                    @if($contenu->date_validation)
                        <small class="text-muted">
                            Validé par {{ $contenu->moderateur->prenom ?? '' }} {{ $contenu->moderateur->nom ?? '' }}
                        </small>
                    @endif
                </div>
                <div class="card-body">
                    <div class="border rounded p-4 bg-light" style="min-height: 200px;">
                        {!! nl2br(e($contenu->texte ?? 'Aucun contenu')) !!}
                    </div>
                </div>
            </div>

            <!-- Médias associés -->
            <div class="card mt-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Médias associés ({{ $contenu->medias_count ?? $contenu->medias->count() }})</h6>
                    @if(($contenu->medias_count ?? $contenu->medias->count()) > 0)
                        <a href="{{ route('media.index') }}?contenu={{ $contenu->id_contenu }}" 
                           class="btn btn-sm btn-outline-primary">
                            Voir tout
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($contenu->medias && $contenu->medias->count() > 0)
                        <div class="row">
                            @foreach($contenu->medias as $media)
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="card h-100">
                                        @if(str_starts_with($media->mime_type ?? '', 'image/'))
                                            <img src="{{ Storage::disk('public')->exists($media->chemin) ? asset('storage/' . $media->chemin) : 'https://via.placeholder.com/400x250?text=Image+non+disponible' }}" 
                                                 class="card-img-top" 
                                                 style="height: 180px; object-fit: cover;"
                                                 alt="{{ $media->description }}"
                                                 onerror="this.src='https://via.placeholder.com/400x250?text=Image+non+disponible'">
                                        @elseif(str_starts_with($media->mime_type ?? '', 'video/'))
                                            <div class="card-img-top position-relative bg-dark" style="height: 180px; overflow: hidden;">
                                                <video src="{{ Storage::disk('public')->exists($media->chemin) ? asset('storage/' . $media->chemin) : '' }}" 
                                                       class="w-100 h-100" 
                                                       style="object-fit: cover;"
                                                       controls
                                                       preload="metadata">
                                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                                </video>
                                            </div>
                                        @elseif(str_starts_with($media->mime_type ?? '', 'audio/'))
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                 style="height: 180px;">
                                                <i class="bi bi-music-note-beamed text-info" style="font-size: 3rem;"></i>
                                            </div>
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                 style="height: 180px;">
                                                <i class="bi bi-file-earmark text-secondary" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="card-body">
                                            <h6 class="card-title text-truncate">{{ $media->description }}</h6>
                                            <p class="card-text small mb-1">
                                                <strong>Type :</strong> {{ $media->typeMedia->nom_media ?? 'Inconnu' }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    {{ $media->created_at ? $media->created_at->format('d/m/Y') : '-' }}
                                                </small>
                                                <a href="{{ Storage::disk('public')->exists($media->chemin) ? asset('storage/' . $media->chemin) : '#' }}" 
                                                   class="btn btn-sm btn-outline-primary" target="_blank" download
                                                   onclick="if(this.getAttribute('href') === '#') { event.preventDefault(); alert('Le fichier n\\'est pas disponible.'); }">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-images display-4 text-muted"></i>
                            <h5 class="text-muted mt-3">Aucun média</h5>
                            <p class="text-muted">Ce contenu n'a pas encore de médias associés.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informations détaillées -->
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Informations détaillées</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td width="30%"><strong>ID :</strong></td>
                            <td>{{ $contenu->id_contenu }}</td>
                        </tr>
                        <tr>
                            <td><strong>Titre :</strong></td>
                            <td>{{ $contenu->titre ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Statut :</strong></td>
                            <td>
                                <span class="badge bg-{{ $statusColors[$contenu->statut] ?? 'secondary' }}">
                                    {{ $statusLabels[$contenu->statut] ?? $contenu->statut }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Région :</strong></td>
                            <td>
                                {{ $contenu->region->nom_region ?? 'Non définie' }}
                                @if($contenu->region)
                                    <br><small class="text-muted">{{ $contenu->region->description ?? '' }}</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Langue :</strong></td>
                            <td>
                                {{ $contenu->langue->nom_langue ?? 'Non définie' }}
                                <small class="text-muted">({{ $contenu->langue->code_langue ?? '' }})</small>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Type de contenu :</strong></td>
                            <td>{{ $contenu->typeContenu->nom_contenu ?? 'Non défini' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Auteur :</strong></td>
                            <td>
                                {{ $contenu->auteur->prenom ?? 'Inconnu' }} {{ $contenu->auteur->nom ?? '' }}
                                <br><small class="text-muted">{{ $contenu->auteur->email ?? '' }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Date de création :</strong></td>
                            <td>{{ $contenu->date_creation ? $contenu->date_creation->format('d/m/Y à H:i') : '-' }}</td>
                        </tr>
                        @if($contenu->date_validation)
                            <tr>
                                <td><strong>Date de validation :</strong></td>
                                <td>{{ $contenu->date_validation->format('d/m/Y à H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Modérateur :</strong></td>
                                <td>
                                    {{ $contenu->moderateur->prenom ?? 'Inconnu' }} {{ $contenu->moderateur->nom ?? '' }}
                                    <br><small class="text-muted">{{ $contenu->moderateur->role->nom_role ?? '' }}</small>
                                </td>
                            </tr>
                        @endif
                        @if($contenu->parent_id)
                            <tr>
                                <td><strong>Contenu parent :</strong></td>
                                <td>
                                    ID: {{ $contenu->parent_id }}
                                    @if($contenu->parent)
                                        - {{ $contenu->parent->titre }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td><strong>Dernière modification :</strong></td>
                            <td>{{ $contenu->updated_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Médias associés :</strong></td>
                            <td>
                                <span class="badge bg-primary">{{ $contenu->medias_count ?? $contenu->medias->count() }}</span>
                            </td>
                        </tr>
                        @if($contenu->commentaires && $contenu->commentaires->count() > 0)
                            <tr>
                                <td><strong>Commentaires :</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ $contenu->commentaires->count() }}</span>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirmation avant suppression
    document.querySelectorAll('form[onsubmit]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir effectuer cette action ?')) {
                e.preventDefault();
                return false;
            }
        });
    });
    
    // Gestion des téléchargements de médias
    document.querySelectorAll('a[download]').forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.getAttribute('href') === '#') {
                e.preventDefault();
                alert('Le fichier média n\'est pas disponible pour le téléchargement.');
            }
        });
    });
});
</script>
@endpush