@extends('layouts.app')

@section('title', 'Modifier la Région')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Modifier la Région</h5>
                        <a href="{{ route('regions.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('regions.update', $region->id_region) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom_region" class="form-label">Nom de la région *</label>
                                    <input type="text" class="form-control @error('nom_region') is-invalid @enderror" 
                                           id="nom_region" name="nom_region" 
                                           value="{{ old('nom_region', $region->nom_region) }}" required>
                                    @error('nom_region')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="population" class="form-label">Population</label>
                                    <input type="number" class="form-control @error('population') is-invalid @enderror" 
                                           id="population" name="population" 
                                           value="{{ old('population', $region->population) }}" 
                                           min="0" step="1">
                                    @error('population')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="superficie" class="form-label">Superficie (km²)</label>
                                    <input type="number" class="form-control @error('superficie') is-invalid @enderror" 
                                           id="superficie" name="superficie" 
                                           value="{{ old('superficie', $region->superficie) }}" 
                                           min="0" step="0.01">
                                    @error('superficie')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="localisation" class="form-label">Localisation</label>
                                    <textarea class="form-control @error('localisation') is-invalid @enderror" 
                                              id="localisation" name="localisation" rows="3">{{ old('localisation', $region->localisation) }}</textarea>
                                    @error('localisation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                </div>

                                <div class="mb-3">
                                    <label for="langues" class="form-label">Langues parlées *</label>
                                    <select class="form-select @error('langues') is-invalid @enderror" 
                                            id="langues" name="langues[]" multiple required size="5">
                                        @foreach($langues as $langue)
                                            <option value="{{ $langue->id_langue }}" 
                                                {{ in_array($langue->id_langue, old('langues', $region->langues->pluck('id_langue')->toArray())) ? 'selected' : '' }}>
                                                {{ $langue->nom_langue }} ({{ $langue->code_langue }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('langues')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    <div class="form-text">
                                        Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs langues.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" required>{{ old('description', $region->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>

                        <!-- Statistiques -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Statistiques de la région</h6>
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <h4 class="text-primary">{{ $region->contenus_count }}</h4>
                                        <small class="text-muted">Contenus</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="text-success">{{ $region->langues_count }}</h4>
                                        <small class="text-muted">Langues</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="text-warning">{{ $region->population ? number_format($region->population, 0, ',', ' ') : '-' }}</h4>
                                        <small class="text-muted">Population</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="text-info">{{ $region->superficie ? number_format($region->superficie, 0, ',', ' ') : '-' }}</h4>
                                        <small class="text-muted">km²</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('regions.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Annuler
                                    </a>
                                    <div class="btn-group">
                                        <a href="{{ route('regions.show', $region->id_region) }}" 
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
@endsection