@extends('layouts.app')

@section('title', 'Ajouter un Média')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ajouter un Nouveau Média</h5>
                        <a href="{{ route('media.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Source du média *</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="source_type" id="source_file" value="file" checked>
                                        <label class="form-check-label" for="source_file">
                                            Télécharger un fichier depuis votre appareil
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="source_type" id="source_url" value="url">
                                        <label class="form-check-label" for="source_url">
                                            Télécharger depuis une URL internet
                                        </label>
                                    </div>
                                </div>

                                <div id="file-input-group" class="mb-3">
                                    <label for="chemin" class="form-label">Fichier *</label>
                                    <input type="file" class="form-control @error('chemin') is-invalid @enderror"
                                           id="chemin" name="chemin"
                                           accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.txt" required>
                                    @error('chemin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    <div class="form-text">
                                        Formats acceptés : images, vidéos (MP4, AVI, MOV, WMV, FLV, MKV, WebM), audio (MP3, WAV, OGG), documents (PDF, Word). <strong>Max 500MB par fichier.</strong>
                                    </div>
                                </div>

                                <div id="url-input-group" class="mb-3" style="display: none;">
                                    <label for="url" class="form-label">URL du média *</label>
                                    <input type="url" class="form-control @error('url') is-invalid @enderror"
                                           id="url" name="url" placeholder="https://example.com/image.jpg">
                                    @error('url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    <div class="form-text">
                                        Entrez l'URL complète du média (image, vidéo, etc.).
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="id_type_media" class="form-label">Type de média *</label>
                                    <select class="form-select @error('id_type_media') is-invalid @enderror" 
                                            id="id_type_media" name="id_type_media" required>
                                        <option value="">Sélectionnez un type</option>
                                        @foreach($typesMedia as $type)
                                            <option value="{{ $type->id_type_media }}" {{ old('id_type_media') == $type->id_type_media ? 'selected' : '' }}>
                                                {{ $type->nom_media }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_type_media')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_contenu" class="form-label">Contenu associé *</label>
                                    <select class="form-select @error('id_contenu') is-invalid @enderror" 
                                            id="id_contenu" name="id_contenu" required>
                                        <option value="">Sélectionnez un contenu</option>
                                        @if(Auth::user()->isAdmin())
                                            @foreach($contenus as $contenu)
                                                <option value="{{ $contenu->id_contenu }}" {{ old('id_contenu') == $contenu->id_contenu ? 'selected' : '' }}>
                                                    {{ $contenu->titre }}
                                                </option>
                                            @endforeach
                                        @else
                                            @foreach(Auth::user()->contenus as $contenu)
                                                <option value="{{ $contenu->id_contenu }}" {{ old('id_contenu') == $contenu->id_contenu ? 'selected' : '' }}>
                                                    {{ $contenu->titre }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('id_contenu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" 
                                              placeholder="Description du média..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                </div>
                            </div>
                        </div>

                        <!-- Aperçu -->
                        <div id="media-preview" class="mb-4"></div>

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('media.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Ajouter le média
                                    </button>
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
// Basculer entre fichier et URL
document.querySelectorAll('input[name="source_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const fileGroup = document.getElementById('file-input-group');
        const urlGroup = document.getElementById('url-input-group');
        const fileInput = document.getElementById('chemin');
        const urlInput = document.getElementById('url');

        if (this.value === 'file') {
            fileGroup.style.display = 'block';
            urlGroup.style.display = 'none';
            fileInput.required = true;
            urlInput.required = false;
            urlInput.value = '';
        } else {
            fileGroup.style.display = 'none';
            urlGroup.style.display = 'block';
            fileInput.required = false;
            urlInput.required = true;
            fileInput.value = '';
        }

        // Réinitialiser l'aperçu
        document.getElementById('media-preview').innerHTML = '';
    });
});

// Prévisualisation du média depuis fichier
document.getElementById('chemin').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('media-preview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let html = '<h6>Aperçu :</h6>';

            if (file.type.startsWith('image/')) {
                html += `
                    <div class="text-center">
                        <img src="${e.target.result}" class="img-thumbnail" style="max-height: 300px;">
                        <div class="mt-2">
                            <small class="text-muted">${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                        </div>
                    </div>
                `;
            } else if (file.type.startsWith('video/')) {
                html += `
                    <div class="text-center">
                        <video src="${e.target.result}" controls class="img-thumbnail" style="max-height: 300px;"></video>
                        <div class="mt-2">
                            <small class="text-muted">${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                        </div>
                    </div>
                `;
            } else if (file.type.startsWith('audio/')) {
                html += `
                    <div class="text-center">
                        <audio src="${e.target.result}" controls class="w-100"></audio>
                        <div class="mt-2">
                            <small class="text-muted">${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                        </div>
                    </div>
                `;
            } else {
                html += `
                    <div class="text-center border rounded p-4 bg-light">
                        <i class="bi bi-file-earmark display-4 text-muted"></i>
                        <div class="mt-2">
                            <strong>${file.name}</strong><br>
                            <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB • ${file.type}</small>
                        </div>
                    </div>
                `;
            }

            preview.innerHTML = html;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});

// Prévisualisation du média depuis URL
document.getElementById('url').addEventListener('input', function(e) {
    const url = e.target.value;
    const preview = document.getElementById('media-preview');

    if (url && url.match(/\.(jpeg|jpg|gif|png|webp|mp4|avi|mov|webm|mp3|wav|pdf|doc|docx)$/i)) {
        let html = '<h6>Aperçu :</h6>';

        if (url.match(/\.(jpeg|jpg|gif|png|webp)$/i)) {
            html += `
                <div class="text-center">
                    <img src="${url}" class="img-thumbnail" style="max-height: 300px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div class="alert alert-warning mt-2" style="display: none;">
                        Impossible de charger l'aperçu de l'image.
                    </div>
                </div>
            `;
        } else if (url.match(/\.(mp4|avi|mov|webm)$/i)) {
            html += `
                <div class="text-center">
                    <video src="${url}" controls class="img-thumbnail" style="max-height: 300px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"></video>
                    <div class="alert alert-warning mt-2" style="display: none;">
                        Impossible de charger l'aperçu de la vidéo.
                    </div>
                </div>
            `;
        } else {
            html += `
                <div class="text-center border rounded p-4 bg-light">
                    <i class="bi bi-link-45deg display-4 text-muted"></i>
                    <div class="mt-2">
                        <strong>URL du média</strong><br>
                        <small class="text-muted">${url}</small>
                    </div>
                </div>
            `;
        }

        preview.innerHTML = html;
    } else if (url) {
        preview.innerHTML = '<div class="alert alert-info">Format non supporté pour l\'aperçu.</div>';
    } else {
        preview.innerHTML = '';
    }
});
</script>
@endpush
@endsection