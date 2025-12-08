<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Culture Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .register-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .register-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
    </style>
</head>
<body>
    <div class="register-container d-flex align-items-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    <div class="card register-card">
                        <div class="card-body p-5">
                            <!-- Logo et titre -->
                            <div class="text-center mb-5">
                                <i class="bi bi-globe-europe-africa display-4 text-primary mb-3"></i>
                                <h2 class="fw-bold">Rejoignez Culture Bénin</h2>
                                <p class="text-muted">Créez votre compte pour découvrir la richesse culturelle du Bénin</p>
                            </div>

                            <!-- Messages d'erreur -->
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Formulaire d'inscription -->
                            <form action="{{ route('register.post') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nom" class="form-label">Nom *</label>
                                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                                   id="nom" name="nom" value="{{ old('nom') }}" required>
                                            @error('nom')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="prenom" class="form-label">Prénom *</label>
                                            <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                                                   id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                                            @error('prenom')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Adresse email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mot_de_passe" class="form-label">Mot de passe *</label>
                                            <input type="password" class="form-control @error('mot_de_passe') is-invalid @enderror" 
                                                   id="mot_de_passe" name="password" required>
                                            @error('mot_de_passe')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mot_de_passe" class="form-label">Confirmation *</label>
                                            <input type="password" class="form-control" 
                                                   id="mot_de_passe" name="password_confirmation" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date_naissance" class="form-label">Date de naissance *</label>
                                            <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" 
                                                   id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}" required>
                                            @error('date_naissance')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sexe" class="form-label">Sexe *</label>
                                            <select class="form-select @error('sexe') is-invalid @enderror" 
                                                    id="sexe" name="sexe" required>
                                                <option value="">Sélectionnez...</option>
                                                <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Homme</option>
                                                <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Femme</option>
                                            </select>
                                            @error('sexe')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="id_langue" class="form-label">Langue préférée *</label>
                                    <select class="form-select @error('id_langue') is-invalid @enderror" 
                                            id="id_langue" name="id_langue" required>
                                        <option value="">Sélectionnez votre langue</option>
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

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="conditions" required>
                                        <label class="form-check-label" for="conditions">
                                            J'accepte les 
                                            <a href="#" class="text-decoration-none">conditions d'utilisation</a> 
                                            et la 
                                            <a href="#" class="text-decoration-none">politique de confidentialité</a>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                    <i class="bi bi-person-plus me-2"></i>
                                    Créer mon compte
                                </button>

                                <div class="text-center">
                                    <p class="mb-0">
                                        Déjà un compte ? 
                                        <a href="{{ route('login') }}" class="text-decoration-none">Se connecter</a>
                                    </p>
                                </div>
                            </form>

                            <hr class="my-4">

                            <div class="text-center">
                                <a href="{{ route('accueil') }}" class="text-muted text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Retour au site public
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Validation de l'âge (au moins 18 ans)
        document.getElementById('date_naissance').addEventListener('change', function() {
            const birthDate = new Date(this.value);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            
            if (age < 18) {
                alert('Vous devez avoir au moins 18 ans pour vous inscrire.');
                this.value = '';
            }
        });
    </script>
</body>
</html>