@extends('layouts.app')

@section('title', $media->description)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <!-- Affichage du média -->
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Visualisation du média</h6>
                </div>
                <div class="card-body text-center">
                    @if(str_starts_with($media->mime_type ?? '', 'image/'))
                        <img src="{{ asset('storage/' . $media->chemin) }}" 
                             class="img-fluid rounded" 
                             style="max-height: 500px;"
                             alt="{{ $media->description }}"
                             onerror="this.src='https://via.placeholder.com/500x300?text=Image+non+disponible'">
                    @elseif(str_starts_with($media->mime_type ?? '', 'video/'))
                        <video src="{{ asset('storage/' . $media->chemin) }}" 
                               controls class="w-100 rounded" 
                               style="max-height: 500px;"></video>
                    @elseif(str_starts_with($media->mime_type ?? '', 'audio/'))
                        <div class="p-4">
                            <i class="bi bi-mic display-1 text-primary mb-3"></i>
                            <audio src="{{ asset('storage/' . $media->chemin) }}" 
                                   controls class="w-100 mt-3"></audio>
                        </div>
                    @else
                        <div class="p-5">
                            <i class="bi bi-file-earmark display-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Document</h5>
                            <p class="text-muted">Ce type de fichier ne peut pas être prévisualisé</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white text-center">
                    <div class="btn-group">
                        <a href="{{ asset('storage/' . $media->chemin) }}" 
                           target="_blank" 
                           class="btn btn-outline-primary">
                            <i class="bi bi-box-arrow-up-right me-2"></i>Ouvrir
                        </a>
                        <a href="{{ route('medias.download', $media->id_media) }}" 
                           class="btn btn-outline-success">
                            <i class="bi bi-download me-2"></i>Télécharger
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Informations du média -->
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Informations du média</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td width="30%"><strong>Description :</strong></td>
                            <td>{{ $media->description }}</td>
                        </tr>
                        <tr>
                            <td><strong>Type :</strong></td>
                            <td>
                               <span class="badge bg-primary">{{ $media->typeMedia?->nom_media ?? 'Type non défini' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Contenu associé :</strong></td>
                            <td>
                                        <a href="{{ route('contenus.show.public', $media->id_contenu) }}" 
                                   class="text-decoration-none">
                                    {{ $media->contenu->titre }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Taille :</strong></td>
                            <td>{{ round(filesize(storage_path('app/public/' . $media->chemin)) / 1024 / 1024, 2) }} MB</td>
                        </tr>
                        <tr>
                            <td><strong>Extension :</strong></td>
                            <td><code>{{ pathinfo($media->chemin, PATHINFO_EXTENSION) }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Date d'upload :</strong></td>
                            <td>{{ $media->created_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Dernière modification :</strong></td>
                            <td>{{ $media->updated_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Chemin :</strong></td>
                            <td>
                                <small class="text-muted">{{ $media->chemin }}</small>
                            </td>
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
                        <a href="{{ route('media.edit', $media->id_media) }}" 
                           class="btn btn-outline-warning">
                            <i class="bi bi-pencil me-2"></i>Modifier
                        </a>

                                <a href="{{ route('contenus.show.public', $media->id_contenu) }}" 
                                    class="btn btn-outline-info">
                            <i class="bi bi-file-text me-2"></i>Voir le contenu
                        </a>

                        <form action="{{ route('media.destroy', $media->id_media) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-outline-danger w-100"
                                    onclick="return confirm('Supprimer ce média ?')">
                                <i class="bi bi-trash me-2"></i>Supprimer
                            </button>
                        </form>

                        <a href="{{ route('media.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour aux médias
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informations techniques -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Informations techniques</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">Type MIME</small>
                                <strong>{{ mime_content_type(storage_path('app/public/' . $media->chemin)) }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">Permissions</small>
                                <strong>{{ substr(sprintf('%o', fileperms(storage_path('app/public/' . $media->chemin))), -4) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection