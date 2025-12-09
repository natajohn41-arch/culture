@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Mon Profil</h5>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4 text-center">
                                <!-- Photo de profil -->
                                <div class="mb-4">
                                    @if(auth()->user()->hasPhoto())
                                        <img src="{{ auth()->user()->photo_url }}" 
                                             class="rounded-circle mb-3" 
                                             style="width: 200px; height: 200px; object-fit: cover;"
                                             id="current-photo"
                                             alt="Photo de profil">
                                    @else
                                        <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3 text-white" 
                                             style="width: 200px; height: 200px;"
                                             id="current-photo">
                                            <i class="bi bi-person-fill" style="font-size: 4rem;"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="mb-3">
                                        <label for="photo" class="form-label">Changer la photo</label>
                                        <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                               id="photo" name="photo" accept="image/*">
                                        @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror>
                                    </div>

                                    <div id="photo-preview" class="mt-3"></div>
                                </div>

                                <!-- Informations de compte -->
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Informations du compte</h6>
                                        <p class="mb-2">
                                            <small class="text-muted">Rôle :</small><br>
                                            <span class="badge bg-{{ auth()->user()->role->nom_role == 'Admin' ? 'danger' : (auth()->user()->role->nom_role == 'Moderateur' ? 'warning' : (auth()->user()->role->nom_role == 'Auteur' ? 'success' : 'secondary')) }}">
                                                {{ auth()->user()->role->nom_role }}
                                            </span>
                                        </p>
                                        <p class="mb-2">
                                            <small class="text-muted">Membre depuis :</small><br>
                                            <strong>{{ auth()->user()->date_inscription->format('d/m/Y') }}</strong>
                                        </p>
                                        <p class="mb-0">
                                            <small class="text-muted">Statut :</small><br>
                                            <span class="badge bg-{{ auth()->user()->statut == 'actif' ? 'success' : 'secondary' }}">
                                                {{ auth()->user()->statut }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <!-- Informations personnelles -->
                                <h6 class="mb-3">Informations personnelles</h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nom" class="form-label">Nom *</label>
                                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                                   id="nom" name="nom" value="{{ old('nom', auth()->user()->nom) }}" required>
                                            @error('nom')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="prenom" class="form-label">Prénom *</label>
                                            <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                                                   id="prenom" name="prenom" value="{{ old('prenom', auth()->user()->prenom) }}" required>
                                            @error('prenom')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email *</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date_naissance" class="form-label">Date de naissance *</label>
                                            <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" 
                                                   id="date_naissance" name="date_naissance" 
                                                   value="{{ old('date_naissance', auth()->user()->date_naissance->format('Y-m-d')) }}" required>
                                            @error('date_naissance')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sexe" class="form-label">Sexe *</label>
                                            <select class="form-select @error('sexe') is-invalid @enderror" 
                                                    id="sexe" name="sexe" required>
                                                <option value="M" {{ old('sexe', auth()->user()->sexe) == 'M' ? 'selected' : '' }}>Homme</option>
                                                <option value="F" {{ old('sexe', auth()->user()->sexe) == 'F' ? 'selected' : '' }}>Femme</option>
                                            </select>
                                            @error('sexe')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="id_langue" class="form-label">Langue préférée *</label>
                                            <select class="form-select @error('id_langue') is-invalid @enderror" 
                                                    id="id_langue" name="id_langue" required>
                                                @foreach($langues as $langue)
                                                    <option value="{{ $langue->id_langue }}" {{ old('id_langue', auth()->user()->id_langue) == $langue->id_langue ? 'selected' : '' }}>
                                                        {{ $langue->nom_langue }} ({{ $langue->code_langue }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_langue')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Statistiques personnelles -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h6 class="mb-3">Mes Statistiques</h6>
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <i class="bi bi-file-text display-6 text-primary mb-2"></i>
                                                    <h4>{{ auth()->user()->contenus->count() }}</h4>
                                                    <small class="text-muted">Contenus créés</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <i class="bi bi-chat-text display-6 text-success mb-2"></i>
                                                    <h4>{{ auth()->user()->commentaires->count() }}</h4>
                                                    <small class="text-muted">Commentaires</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <i class="bi bi-check-circle display-6 text-warning mb-2"></i>
                                                    <h4>{{ auth()->user()->contenus->where('statut', 'valide')->count() }}</h4>
                                                    <small class="text-muted">Contenus validés</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                                <i class="bi bi-x-circle me-2"></i>Annuler
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-2"></i>Mettre à jour
                                            </button>
                                        </div>
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
// Prévisualisation de la nouvelle photo
document.getElementById('photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('photo-preview');
    
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="alert alert-info">
                    <h6>Nouvel aperçu :</h6>
                    <img src="${e.target.result}" class="img-thumbnail rounded-circle mt-2" style="width: 100px; height: 100px; object-fit: cover;">
                    <div class="mt-2">
                        <small class="text-muted">${file.name}</small>
                    </div>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});
</script>
@endpush
@endsection