@extends('layouts.app')

@section('title', 'Créer un Contenu')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Créer un Nouveau Contenu</h5>
                        <a href="{{ route('contenu.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('contenu.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <!-- Informations de base -->
                                <div class="mb-4">
                                    <label for="titre" class="form-label">Titre du contenu *</label>
                                    <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                                           id="titre" name="titre" value="{{ old('titre') }}" required>
                                    @error('titre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="texte" class="form-label">Contenu *</label>
                                    <textarea class="form-control @error('texte') is-invalid @enderror" 
                                              id="texte" name="texte" rows="12" required>{{ old('texte') }}</textarea>
                                    @error('texte')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Utilisez le format Markdown pour formater votre texte.
                                    </div>
                                </div>

                                <!-- Médias -->
                                <div class="mb-4">
                                    <label for="medias" class="form-label">Médias associés</label>
                                    <input type="file" class="form-control @error('medias.*') is-invalid @enderror" 
                                           id="medias" name="medias[]" multiple accept="image/*,video/*,audio/*,.pdf,.doc,.docx">
                                    @error('medias.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Formats acceptés : images (JPEG, PNG, GIF), vidéos (MP4, AVI, MOV), audio, PDF, Word. Max 10MB par fichier.
                                    </div>
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
                                            <select class="form-select @error('id_region') is-invalid @enderror" 
                                                    id="id_region" name="id_region" required>
                                                <option value="">Sélectionnez une région</option>
                                                @foreach($regions as $region)
                                                    <option value="{{ $region->id_region }}" {{ old('id_region') == $region->id_region ? 'selected' : '' }}>
                                                        {{ $region->nom_region }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_region')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="id_langue" class="form-label">Langue *</label>
                                            <select class="form-select @error('id_langue') is-invalid @enderror" 
                                                    id="id_langue" name="id_langue" required>
                                                <option value="">Sélectionnez une langue</option>
                                                @foreach($langues as $langue)
                                                    <option value="{{ $langue->id_langue }}" {{ old('id_langue') == $langue->id_langue ? 'selected' : '' }}>
                                                        {{ $langue->nom_langue }} ({{ $langue->code_langue }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_langue')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="id_type_contenu" class="form-label">Type de contenu *</label>
                                            <select class="form-select @error('id_type_contenu') is-invalid @enderror" 
                                                    id="id_type_contenu" name="id_type_contenu" required>
                                                <option value="">Sélectionnez un type</option>
                                                @foreach($typesContenu as $type)
                                                    <option value="{{ $type->id_type_contenu }}" {{ old('id_type_contenu') == $type->id_type_contenu ? 'selected' : '' }}>
                                                        {{ $type->nom_contenu }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_type_contenu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="parent_id" class="form-label">Contenu parent</label>
                                            <select class="form-select @error('parent_id') is-invalid @enderror" 
                                                    id="parent_id" name="parent_id">
                                                <option value="">Aucun (contenu principal)</option>
                                                @foreach($contenusParents = \App\Models\Contenu::whereNull('parent_id')->get() as $parent)
                                                    <option value="{{ $parent->id_contenu }}" {{ old('parent_id') == $parent->id_contenu ? 'selected' : '' }}>
                                                        {{ $parent->titre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Statut (seulement pour les admins) -->
                                        @if(Auth::user()->isAdmin())
                                            <div class="mb-3">
                                                <label for="statut" class="form-label">Statut</label>
                                                <select class="form-select @error('statut') is-invalid @enderror" 
                                                        id="statut" name="statut">
                                                    <option value="valide" {{ old('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                                                    <option value="en_attente" {{ old('statut') == 'en_attente' ? 'selected' : (old('statut') ? '' : 'selected') }}>En attente</option>
                                                    <option value="rejete" {{ old('statut') == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                                                </select>
                                                @error('statut')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @else
                                            <input type="hidden" name="statut" value="en_attente">
                                            <div class="alert alert-info">
                                                <small>
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    Votre contenu sera soumis à validation avant publication.
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>
                                        @if(Auth::user()->isAdmin())
                                            Créer et Publier
                                        @else
                                            Soumettre à Validation
                                        @endif
                                    </button>
                                    <a href="{{ route('contenu.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Annuler
                                    </a>
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
// Prévisualisation des médias
document.getElementById('medias').addEventListener('change', function(e) {
    const files = e.target.files;
    const previewContainer = document.getElementById('media-preview');
    
    if (!previewContainer) {
        const previewDiv = document.createElement('div');
        previewDiv.id = 'media-preview';
        previewDiv.className = 'mt-3';
        this.parentNode.appendChild(previewDiv);
    }
    
    document.getElementById('media-preview').innerHTML = '';
    
    if (files.length > 0) {
        let html = '<h6>Prévisualisation :</h6><div class="row g-2">';
        
        for (let file of files) {
            if (file.type.startsWith('image/')) {
                html += `
                    <div class="col-3">
                        <img src="${URL.createObjectURL(file)}" class="img-thumbnail" style="height: 80px; object-fit: cover;">
                        <small class="d-block text-truncate">${file.name}</small>
                    </div>
                `;
            } else {
                html += `
                    <div class="col-3">
                        <div class="border rounded p-2 text-center bg-light" style="height: 80px;">
                            <i class="bi bi-file-earmark"></i>
                            <small class="d-block text-truncate">${file.name}</small>
                        </div>
                    </div>
                `;
            }
        }
        
        html += '</div>';
        document.getElementById('media-preview').innerHTML = html;
    }
});
</script>
@endpush
@endsection