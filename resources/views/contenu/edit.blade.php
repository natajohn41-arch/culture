@extends('layouts.app')

@section('title', 'Éditer un contenu - Culture CMS')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="bi bi-file-text me-2"></i>Éditer le contenu</h1>
    
    <!-- CORRECTION DU BOUTON RETOUR -->
    @if(Auth::user()->isAdmin())
        <a href="{{ route('contenus.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
    @else
        <a href="{{ route('mes.contenus.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
    @endif
</div>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Détails du contenu
                </div>
                <div class="card-body">

                    <!-- MESSAGE DEBUG -->
                    <div class="alert alert-info mb-3">
                        <small>
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Information :</strong> 
                            Vous êtes connecté en tant que <strong>{{ Auth::user()->role->nom_role }}</strong>.
                            @if(Auth::user()->isAuthor())
                                Le formulaire sera envoyé à la route des auteurs.
                            @endif
                        </small>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- CORRECTION CRITIQUE : FORMULAIRE AVEC LA BONNE ROUTE -->
                    <form action="{{ Auth::user()->isAuthor() ? route('mes.contenus.update', $contenu->id_contenu) : route('contenus.update', $contenu->id_contenu) }}" 
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="titre" class="form-label">Titre *</label>
                                    <input type="text" name="titre" id="titre" 
                                           value="{{ old('titre', $contenu->titre) }}" 
                                           class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="texte" class="form-label">Contenu *</label>
                                    <textarea name="texte" id="texte" class="form-control" 
                                              rows="10" required>{{ old('texte', $contenu->texte) }}</textarea>
                                    <div class="form-text">
                                        Utilisez le format Markdown pour formater votre texte.
                                    </div>
                                </div>

                                <!-- SECTION MÉDIAS EXISTANTS -->
                                @if($contenu->medias && $contenu->medias->count() > 0)
                                    <div class="mb-4">
                                        <label class="form-label d-block">Médias existants</label>
                                        <div class="row g-2">
                                            @foreach($contenu->medias as $media)
                                                <div class="col-md-3">
                                                    <div class="card border">
                                                        @if(str_starts_with($media->mime_type ?? '', 'image/'))
                                                            <img src="{{ Storage::disk('public')->exists($media->chemin) ? asset('storage/' . $media->chemin) : 'https://via.placeholder.com/150' }}" 
                                                                 class="card-img-top" 
                                                                 style="height: 100px; object-fit: cover;" 
                                                                 alt="{{ $media->description }}"
                                                                 onerror="this.src='https://via.placeholder.com/150'">
                                                        @elseif(str_starts_with($media->mime_type ?? '', 'video/'))
                                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                                 style="height: 100px;">
                                                                <i class="bi bi-play-circle text-primary" style="font-size: 2rem;"></i>
                                                            </div>
                                                        @else
                                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                                 style="height: 100px;">
                                                                <i class="bi bi-file-earmark text-secondary" style="font-size: 2rem;"></i>
                                                            </div>
                                                        @endif
                                                        <div class="card-body p-2">
                                                            <small class="d-block text-truncate">{{ $media->description }}</small>
                                                            <div class="form-check mt-1">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="medias_supprimer[]" 
                                                                       value="{{ $media->id_media }}" 
                                                                       id="delete_media_{{ $media->id_media }}">
                                                                <label class="form-check-label text-danger small" 
                                                                       for="delete_media_{{ $media->id_media }}">
                                                                    <i class="bi bi-trash me-1"></i>Supprimer
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- NOUVEAUX MÉDIAS -->
                                <div class="mb-4">
                                    <label for="medias" class="form-label">Ajouter de nouveaux médias</label>
                                    <input type="file" class="form-control" 
                                           id="medias" name="medias[]" 
                                           multiple 
                                           accept="image/*,video/*,audio/*,.pdf,.doc,.docx">
                                    <div class="form-text">
                                        Formats acceptés : images (JPEG, PNG, GIF), vidéos (MP4, AVI, MOV), audio, PDF, Word. Max 100MB par fichier.
                                    </div>
                                    <!-- Prévisualisation -->
                                    <div id="media-preview" class="mt-3"></div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Métadonnées -->
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Métadonnées</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="id_region" class="form-label">Région *</label>
                                            <select name="id_region" id="id_region" class="form-select" required>
                                                <option value="">-- Sélectionner une région --</option>
                                                @foreach($regions as $region)
                                                    <option value="{{ $region->id_region }}" 
                                                            {{ old('id_region', $contenu->id_region) == $region->id_region ? 'selected' : '' }}>
                                                        {{ $region->nom_region }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="id_langue" class="form-label">Langue *</label>
                                            <select name="id_langue" id="id_langue" class="form-select" required>
                                                <option value="">-- Sélectionner une langue --</option>
                                                @foreach($langues as $langue)
                                                    <option value="{{ $langue->id_langue }}" 
                                                            {{ old('id_langue', $contenu->id_langue) == $langue->id_langue ? 'selected' : '' }}>
                                                        {{ $langue->nom_langue }} ({{ $langue->code_langue }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="id_type_contenu" class="form-label">Type de contenu *</label>
                                            <select class="form-select" id="id_type_contenu" name="id_type_contenu" required>
                                                <option value="">Sélectionnez un type</option>
                                                @foreach($typesContenu as $type)
                                                    <option value="{{ $type->id_type_contenu }}" 
                                                            {{ old('id_type_contenu', $contenu->id_type_contenu) == $type->id_type_contenu ? 'selected' : '' }}>
                                                        {{ $type->nom_contenu }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="parent_id" class="form-label">Contenu parent</label>
                                            <select name="parent_id" id="parent_id" class="form-select">
                                                <option value="" selected>-- Aucun (contenu principal) --</option>
                                                @foreach($contenus as $c)
                                                    @if($c->id_contenu != $contenu->id_contenu)
                                                        <option value="{{ $c->id_contenu }}" 
                                                                {{ old('parent_id', $contenu->parent_id) == $c->id_contenu ? 'selected' : '' }}>
                                                            {{ $c->titre }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Auteur (uniquement pour les admins) -->
                                        @if(Auth::check() && Auth::user()->isAdmin())
                                            <div class="mb-3">
                                                <label for="id_auteur" class="form-label">Auteur *</label>
                                                <select name="id_auteur" id="id_auteur" class="form-select" required>
                                                    <option value="">-- Sélectionner un auteur --</option>
                                                    @foreach($utilisateurs as $user)
                                                        <option value="{{ $user->id_utilisateur }}" 
                                                                {{ old('id_auteur', $contenu->id_auteur) == $user->id_utilisateur ? 'selected' : '' }}>
                                                            {{ $user->prenom }} {{ $user->nom }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <input type="hidden" name="id_auteur" value="{{ $contenu->id_auteur }}">
                                        @endif

                                        <!-- Statut -->
                                        @if(Auth::check() && Auth::user()->isAdmin())
                                            <div class="mb-3">
                                                <label for="statut" class="form-label">Statut *</label>
                                                <select name="statut" id="statut" class="form-select" required>
                                                    <option value="en_attente" {{ old('statut', $contenu->statut) == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                                    <option value="valide" {{ old('statut', $contenu->statut) == 'valide' ? 'selected' : '' }}>Validé</option>
                                                    <option value="rejete" {{ old('statut', $contenu->statut) == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                                                </select>
                                            </div>

                                            <!-- Modérateur et date de validation -->
                                            @if($contenu->statut == 'valide')
                                                <div class="mb-3">
                                                    <label for="id_moderateur" class="form-label">Modérateur</label>
                                                    <select name="id_moderateur" id="id_moderateur" class="form-select">
                                                        <option value="">-- Aucun --</option>
                                                        @foreach($utilisateurs as $user)
                                                            @if($user->isModerator() || $user->isAdmin())
                                                                <option value="{{ $user->id_utilisateur }}" 
                                                                        {{ old('id_moderateur', $contenu->id_moderateur) == $user->id_utilisateur ? 'selected' : '' }}>
                                                                    {{ $user->prenom }} {{ $user->nom }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="date_validation" class="form-label">Date de validation</label>
                                                    <input type="datetime-local" name="date_validation" id="date_validation" 
                                                           value="{{ old('date_validation', $contenu->date_validation ? $contenu->date_validation->format('Y-m-d\TH:i') : '') }}" 
                                                           class="form-control">
                                                </div>
                                            @endif
                                        @else
                                            <!-- Pour les non-admins, statut caché -->
                                            <input type="hidden" name="statut" value="{{ $contenu->statut }}">
                                            @if($contenu->statut == 'en_attente')
                                                <div class="alert alert-info">
                                                    <small>
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        Votre contenu est en attente de validation.
                                                    </small>
                                                </div>
                                            @endif
                                        @endif

                                        <!-- Date de création -->
                                        <div class="mb-3">
                                            <label for="date_creation" class="form-label">Date de création</label>
                                            <input type="datetime-local" name="date_creation" id="date_creation" 
                                                   value="{{ old('date_creation', $contenu->date_creation->format('Y-m-d\TH:i')) }}" 
                                                   class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>
                                        @if(Auth::user()->isAuthor())
                                            Mettre à jour
                                        @else
                                            Enregistrer
                                        @endif
                                    </button>
                                    
                                    @if(Auth::check() && Auth::user()->isAdmin())
                                        <a href="{{ route('contenus.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-x-circle me-2"></i>Annuler
                                        </a>
                                    @else
                                        <a href="{{ route('mes.contenus.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-x-circle me-2"></i>Annuler
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Prévisualisation des nouveaux médias
document.getElementById('medias').addEventListener('change', function(e) {
    const files = e.target.files;
    const previewContainer = document.getElementById('media-preview');
    
    previewContainer.innerHTML = '';
    
    if (files.length > 0) {
        let html = '<h6 class="mt-2">Nouveaux fichiers :</h6><div class="row g-2">';
        
        for (let file of files) {
            if (file.type.startsWith('image/')) {
                html += `
                    <div class="col-3">
                        <img src="${URL.createObjectURL(file)}" 
                             class="img-thumbnail" 
                             style="height: 80px; object-fit: cover;"
                             alt="${file.name}">
                        <small class="d-block text-truncate">${file.name}</small>
                    </div>
                `;
            } else if (file.type.startsWith('video/')) {
                html += `
                    <div class="col-3">
                        <div class="border rounded p-2 text-center bg-light" style="height: 80px;">
                            <i class="bi bi-play-circle display-6 text-primary"></i>
                            <small class="d-block text-truncate">${file.name}</small>
                        </div>
                    </div>
                `;
            } else {
                html += `
                    <div class="col-3">
                        <div class="border rounded p-2 text-center bg-light" style="height: 80px;">
                            <i class="bi bi-file-earmark display-6 text-secondary"></i>
                            <small class="d-block text-truncate">${file.name}</small>
                        </div>
                    </div>
                `;
            }
        }
        
        html += '</div>';
        previewContainer.innerHTML = html;
    }
});

// Confirmation pour suppression des médias
document.addEventListener('DOMContentLoaded', function() {
    const deleteCheckboxes = document.querySelectorAll('input[name="medias_supprimer[]"]');
    
    deleteCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                const mediaName = this.closest('.card').querySelector('small').textContent;
                if (!confirm(`Êtes-vous sûr de vouloir supprimer "${mediaName.trim()}" ?`)) {
                    this.checked = false;
                }
            }
        });
    });
});
</script>
@endpush
@endsection