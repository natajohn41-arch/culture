@extends('layouts.app')

@section('title', 'Modifier l\'Utilisateur')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Modifier l'Utilisateur</h5>
                        <a href="{{ route('utilisateurs.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('utilisateurs.update', $utilisateur->id_utilisateur) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Photo actuelle -->
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                @if($utilisateur->hasPhoto())
                                    <img src="{{ $utilisateur->photo_url }}" 
                                         class="rounded-circle mb-3" 
                                         style="width: 150px; height: 150px; object-fit: cover;"
                                         alt="Photo de profil">
                                    <div>
                                        <small class="text-muted">Photo actuelle</small>
                                    </div>
                                @else
                                    <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3 text-white" 
                                         style="width: 150px; height: 150px;">
                                        <i class="bi bi-person-fill" style="font-size: 3rem;"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted">Aucune photo</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Informations personnelles -->
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom *</label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" value="{{ old('nom', $utilisateur->nom) }}" required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="prenom" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                                           id="prenom" name="prenom" value="{{ old('prenom', $utilisateur->prenom) }}" required>
                                    @error('prenom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $utilisateur->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Champ mot de passe optionnel -->
                                <div class="mb-3">
                                    <label for="mot_de_passe" class="form-label">Nouveau mot de passe</label>
                                    <input type="password" class="form-control @error('mot_de_passe') is-invalid @enderror" 
                                           id="mot_de_passe" name="mot_de_passe" 
                                           placeholder="Laisser vide pour ne pas modifier">
                                    @error('mot_de_passe')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Laisser vide pour conserver le mot de passe actuel.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Métadonnées -->
                                <div class="mb-3">
                                    <label for="date_naissance" class="form-label">Date de naissance *</label>
                                    <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" 
                                           id="date_naissance" name="date_naissance" 
                                           value="{{ old('date_naissance', $utilisateur->date_naissance ? $utilisateur->date_naissance->format('Y-m-d') : '') }}" required>
                                    @error('date_naissance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="sexe" class="form-label">Sexe *</label>
                                    <select class="form-select @error('sexe') is-invalid @enderror" 
                                            id="sexe" name="sexe" required>
                                        <option value="M" {{ old('sexe', $utilisateur->sexe) == 'M' ? 'selected' : '' }}>Homme</option>
                                        <option value="F" {{ old('sexe', $utilisateur->sexe) == 'F' ? 'selected' : '' }}>Femme</option>
                                    </select>
                                    @error('sexe')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="id_langue" class="form-label">Langue préférée *</label>
                                    <select class="form-select @error('id_langue') is-invalid @enderror" 
                                            id="id_langue" name="id_langue" required>
                                        @foreach($langues as $langue)
                                            <option value="{{ $langue->id_langue }}" {{ old('id_langue', $utilisateur->id_langue) == $langue->id_langue ? 'selected' : '' }}>
                                                {{ $langue->nom_langue }} ({{ $langue->code_langue }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_langue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="id_role" class="form-label">Rôle *</label>
                                    <select class="form-select @error('id_role') is-invalid @enderror" 
                                            id="id_role" name="id_role" required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id_role }}" {{ old('id_role', $utilisateur->id_role) == $role->id_role ? 'selected' : '' }}>
                                                {{ $role->nom_role }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut *</label>
                                    <select class="form-select @error('statut') is-invalid @enderror" 
                                            id="statut" name="statut" required>
                                        <option value="actif" {{ old('statut', $utilisateur->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactif" {{ old('statut', $utilisateur->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="photo" class="form-label">Nouvelle photo</label>
                                    <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                           id="photo" name="photo" accept="image/*">
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    <div class="form-text">
                                        Laisser vide pour conserver la photo actuelle.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations de compte -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Informations du compte</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">Date d'inscription :</small>
                                                <br>
                                                <strong>{{ $utilisateur->date_inscription->format('d/m/Y à H:i') }}</strong>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Dernière modification :</small>
                                                <br>
                                                <strong>
                                                    {{ $utilisateur->updated_at ? $utilisateur->updated_at->format('d/m/Y à H:i') : '—' }}
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('utilisateurs.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Annuler
                                    </a>
                                    <div class="btn-group">
                                        <a href="{{ route('utilisateurs.show', $utilisateur->id_utilisateur) }}" 
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
// Prévisualisation de la nouvelle photo
document.getElementById('photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewContainer = document.getElementById('new-photo-preview');
    
    if (!previewContainer) {
        const previewDiv = document.createElement('div');
        previewDiv.id = 'new-photo-preview';
        previewDiv.className = 'mt-3 text-center';
        this.parentNode.appendChild(previewDiv);
    }
    
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('new-photo-preview').innerHTML = `
                <h6>Nouvel aperçu :</h6>
                <img src="${e.target.result}" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                <div class="mt-2">
                    <small class="text-muted">${file.name}</small>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('new-photo-preview').innerHTML = '';
    }
});
</script>
@endpush
@endsection