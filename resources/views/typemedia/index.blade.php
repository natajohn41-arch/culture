@extends('layouts.app')

@section('title', 'Types de Média')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3">Types de Média</h1>
                    <p class="text-muted mb-0">Gestion des formats de médias acceptés</p>
                </div>
                @can('create', App\Models\TypeMedia::class)
                    <a href="{{ route('type-medias.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Nouveau Type
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Types par défaut -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-image display-4 text-primary mb-3"></i>
                    <h5>Image</h5>
                    <small class="text-muted">JPEG, PNG, GIF, WebP</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <i class="bi bi-camera-video display-4 text-success mb-3"></i>
                    <h5>Vidéo</h5>
                    <small class="text-muted">MP4, AVI, MOV, WebM</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-mic display-4 text-warning mb-3"></i>
                    <h5>Audio</h5>
                    <small class="text-muted">MP3, WAV, OGG, M4A</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark display-4 text-info mb-3"></i>
                    <h5>Document</h5>
                    <small class="text-muted">PDF, DOC, DOCX, TXT</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des types de média -->
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="mb-0">Types de Média ({{ $typeMedias->count() }})</h6>
        </div>
        <div class="card-body">
            @if($typeMedias->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Extensions</th>
                                <th>Médias</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($typeMedias as $type)
                                <tr>
                                    <td>
                                        <strong>{{ $type->nom_media }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $type->description ?? 'Aucune description' }}
                                        </small>
                                    </td>
                                    <td>
                                        <code>{{ $type->extensions ?? 'Tous formats' }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $type->medias_count > 0 ? 'primary' : 'secondary' }}">
                                            {{ $type->medias_count }} média(s)
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('type-medias.show', $type->id_type_media) }}" 
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            @can('update', $type)
                                                <a href="{{ route('type-medias.edit', $type->id_type_media) }}" 
                                                   class="btn btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan

                                            @can('delete', $type)
                                                @if($type->medias_count == 0)
                                                    <form action="{{ route('type-medias.destroy', $type->id_type_media) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-outline-danger" 
                                                                title="Supprimer"
                                                                onclick="return confirm('Supprimer ce type de média ?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-outline-secondary" 
                                                            title="Impossible de supprimer (utilisé dans des médias)"
                                                            disabled>
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-collection display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">Aucun type de média</h4>
                    <p class="text-muted">Aucun type de média n'a été créé pour le moment.</p>
                        @can('create', App\Models\TypeMedia::class)
                        <a href="{{ route('type-medias.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Créer le premier type
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection