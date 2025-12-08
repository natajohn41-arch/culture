@extends('layouts.app')

@section('title', 'Modifier la Langue')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Modifier la Langue</h5>
                        <a href="{{ route('langues.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('langues.update', $langue->id_langue) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom_langue" class="form-label">Nom de la langue *</label>
                                    <input type="text" class="form-control @error('nom_langue') is-invalid @enderror" 
                                           id="nom_langue" name="nom_langue" 
                                           value="{{ old('nom_langue', $langue->nom_langue) }}" required>
                                    @error('nom_langue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="code_langue" class="form-label">Code de la langue *</label>
                                    <input type="text" class="form-control @error('code_langue') is-invalid @enderror" 
                                           id="code_langue" name="code_langue" 
                                           value="{{ old('code_langue', $langue->code_langue) }}" 
                                           placeholder="Ex: fr, fon, yor..." required>
                                    @error('code_langue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    <div class="form-text">
                                        Code ISO 639 généralement sur 2 ou 3 lettres.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="regions" class="form-label">Régions où elle est parlée *</label>
                                    <select class="form-select @error('regions') is-invalid @enderror" 
                                            id="regions" name="regions[]" multiple required size="5">
                                        @foreach($regions as $region)
                                            <option value="{{ $region->id_region }}" 
                                                {{ in_array($region->id_region, old('regions', $langue->regions->pluck('id_region')->toArray())) ? 'selected' : '' }}>
                                                {{ $region->nom_region }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('regions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    <div class="form-text">
                                        Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs régions.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" required>{{ old('description', $langue->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>

                        <!-- Statistiques -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Statistiques de la langue</h6>
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <h4 class="text-primary">{{ $langue->contenus_count }}</h4>
                                        <small class="text-muted">Contenus</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="text-success">{{ $langue->regions_count }}</h4>
                                        <small class="text-muted">Régions</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="text-warning">{{ $langue->utilisateurs_count ?? 0 }}</h4>
                                        <small class="text-muted">Utilisateurs</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h4 class="text-info">{{ $langue->created_at->diffForHumans() }}</h4>
                                        <small class="text-muted">Créée</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('langues.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Annuler
                                    </a>
                                    <div class="btn-group">
                                        <a href="{{ route('langues.show', $langue->id_langue) }}" 
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