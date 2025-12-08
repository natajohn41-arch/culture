@extends('layouts.app')

@section('title', 'Modifier le Média')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Modifier le Média</h5>
                        <a href="{{ route('media.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('media.update', $media->id_media) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Média actuel -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6>Média actuel :</h6>
                                <div class="card">
                                    <div class="card-body text-center">
                                        @if(str_contains($media->chemin, 'image/') || in_array(pathinfo($media->chemin, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <img src="{{ asset('storage/' . $media->chemin) }}" 
                                                 class="img-thumbnail" 
                                                 style="max-height: 300px;"
                                                 alt="{{ $media->description }}">
                                        @elseif(str_contains($media->chemin, 'video/') || in_array(pathinfo($media->chemin, PATHINFO_EXTENSION), ['mp4', 'avi', 'mov']))
                                            <div class="text-center text-muted">
                                                <i class="bi bi-camera-video display-4"></i>
                                                <div class="mt-2">Vidéo</div>
                                            </div>
                                        @elseif(str_contains($media->chemin, 'audio/') || in_array(pathinfo($media->chemin, PATHINFO_EXTENSION), ['mp3', 'wav', 'ogg']))
                                            <div class="text-center text-muted">
                                                <i class="bi bi-mic display-4"></i>
                                                <div class="mt-2">Audio</div>
                                            </div>
                                        @else
                                            <div class="text-center text-muted">
                                                <i class="bi bi-file-earmark display-4"></i>
                                                <div class="mt-2">Document</div>
                                            </div>
                                        @endif
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                {{ $media->description }} • 
                                                {{ round(filesize(storage_path('app/public/' . $media->chemin)) / 1024 / 1024, 2) }} MB
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="chemin" class="form-label">Nouveau fichier</label>
                                    <input type="file" class="form-control @error('chemin') is-invalid @enderror" 
                                           id="chemin" name="chemin" 
                                           accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.txt">
                                    @error('chemin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    <div class="form-text">
                                        Laisser vide pour conserver le fichier actuel.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="id_type_media" class="form-label">Type de média *</label>
                                    <select class="form-select @error('id_type_media') is-invalid @enderror" 
                                            id="id_type_media" name="id_type_media" required>
                                        @foreach($typesMedia as $type)
                                            <option value="{{ $type->id_type_media }}" {{ old('id_type_media', $media->id_type_media) == $type->id_type_media ? 'selected' : '' }}>
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
                                        @if(Auth::user()->isAdmin())
                                            @foreach($contenus as $contenu)
                                                <option value="{{ $contenu->id_contenu }}" {{ old('id_contenu', $media->id_contenu) == $contenu->id_contenu ? 'selected' : '' }}>
                                                    {{ $contenu->titre }}
                                                </option>
                                            @endforeach
                                        @else
                                            @foreach(Auth::user()->contenus as $contenu)
                                                <option value="{{ $contenu->id_contenu }}" {{ old('id_contenu', $media->id_contenu) == $contenu->id_contenu ? 'selected' : '' }}>
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
                                              id="description" name="description" rows="3" required>{{ old('description', $media->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                </div>
                            </div>
                        </div>

                        <!-- Aperçu du nouveau fichier -->
                        <div id="new-media-preview" class="mb-4"></div>

                        <!-- Informations techniques -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Informations techniques</h6>
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <h6 class="text-primary">{{ $media->typeMedia->nom_media }}</h6>
                                        <small class="text-muted">Type</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="text-success">{{ round(filesize(storage_path('app/public/' . $media->chemin)) / 1024 / 1024, 2) }} MB</h6>
                                        <small class="text-muted">Taille</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="text-warning">{{ $media->created_at->format('d/m/Y') }}</h6>
                                        <small class="text-muted">Créé</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="text-info">{{ pathinfo($media->chemin, PATHINFO_EXTENSION) }}</h6>
                                        <small class="text-muted">Extension</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('media.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Annuler
                                    </a>
                                    <div class="btn-group">
                                        <a href="{{ route('media.show', $media->id_media) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="bi bi-eye me-2"></i>Voir
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-2"></i>Modifier
                                        </button>
                                    </div>
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
// Prévisualisation du nouveau média
document.getElementById('chemin').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('new-media-preview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let html = '<h6>Nouvel aperçu :</h6>';
            
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
</script>
@endpush
@endsection