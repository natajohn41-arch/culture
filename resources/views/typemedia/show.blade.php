@extends('layouts.app')

@section('title', $typeMedia->nom_media)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <!-- Carte d'information -->
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-collection display-4 text-primary mb-3"></i>
                    <h3>{{ $typeMedia->nom_media }}</h3>
                    
                    @if($typeMedia->description)
                        <p class="text-muted mt-3">{{ $typeMedia->description }}</p>
                    @endif

                    @if($typeMedia->extensions)
                        <div class="mt-3">
                            <code>{{ $typeMedia->extensions }}</code>
                        </div>
                    @endif

                    <div class="mt-4">
                        <span class="badge bg-primary fs-6">{{ $typeMedia->medias_count }} média(s)</span>
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
                        <a href="{{ route('type-medias.edit', $typeMedia->id_type_media) }}" 
                           class="btn btn-outline-warning">
                            <i class="bi bi-pencil me-2"></i>Modifier
                        </a>
                        
                        @if($typeMedia->medias_count == 0)
                            <form action="{{ route('typemedia.destroy', $typeMedia->id_type_media) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Supprimer ce type de média ?')">
                                    <i class="bi bi-trash me-2"></i>Supprimer
                                </button>
                            </form>
                        @else
                            <button class="btn btn-outline-secondary" disabled>
                                <i class="bi bi-trash me-2"></i>Impossible de supprimer
                            </button>
                        @endif

                        <a href="{{ route('type-medias.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Derniers médias de ce type -->
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Médias de ce type ({{ $typeMedia->medias_count }})</h6>
                    <a href="{{ route('medias.index') }}?type={{ $typeMedia->id_type_media }}" 
                       class="btn btn-sm btn-outline-primary">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    @if($typeMedia->medias_count > 0)
                        <div class="row">
                            @foreach($typeMedia->medias->take(6) as $media)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                                             style="height: 120px;">
                                            @if(str_contains($media->chemin, 'image/'))
                                                <img src="{{ asset('storage/' . $media->chemin) }}" 
                                                     class="img-fluid" 
                                                     style="max-height: 100%; object-fit: cover;"
                                                     alt="{{ $media->description }}">
                                            @else
                                                <i class="bi bi-file-earmark display-4 text-muted"></i>
                                            @endif
                                        </div>
                                        <div class="card-body p-2">
                                            <small class="d-block text-truncate">{{ $media->description }}</small>
                                            <small class="text-muted">
                                                {{ round(filesize(storage_path('app/public/' . $media->chemin)) / 1024, 1) }} KB
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-file-earmark display-4 text-muted"></i>
                            <h5 class="text-muted mt-3">Aucun média</h5>
                            <p class="text-muted">Aucun média n'utilise encore ce type.</p>
                            <a href="{{ route('medias.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Ajouter un média
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informations détaillées -->
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Informations</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td width="30%"><strong>Nom :</strong></td>
                            <td>{{ $typeMedia->nom_media }}</td>
                        </tr>
                        <tr>
                            <td><strong>Description :</strong></td>
                            <td>{{ $typeMedia->description ?? 'Aucune description' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Extensions :</strong></td>
                            <td>
                                <code>{{ $typeMedia->extensions ?? 'Tous formats acceptés' }}</code>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Date de création :</strong></td>
                            <td>{{ $typeMedia->created_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Dernière modification :</strong></td>
                            <td>{{ $typeMedia->updated_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Médias associés :</strong></td>
                            <td>
                                <span class="badge bg-primary">{{ $typeMedia->medias_count }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection