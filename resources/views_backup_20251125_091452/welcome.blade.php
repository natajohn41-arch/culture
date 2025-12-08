<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Culture - Système de Gestion de Contenu</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .btn-culture {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
        }
        .btn-culture:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .nav-link {
            color: #333 !important;
            font-weight: 500;
        }
        .nav-link:hover {
            color: #667eea !important;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-globe-europe-africa me-2 text-primary"></i>
                Culture
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fonctionnalités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stats">Statistiques</a>
                    </li>
                </ul>
                <div class="d-flex ms-3">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Inscription</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Gérez votre contenu culturel avec efficacité
                    </h1>
                    <p class="lead mb-4">
                        Une plateforme complète pour gérer vos contenus, médias, régions et utilisateurs. 
                        Simplifiez votre gestion culturelle avec notre système intuitif.
                    </p>
                    <div class="d-flex gap-3">
                        
                        <a href="#features" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-info-circle me-2"></i>En savoir plus
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="position-relative">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-journal-text text-white fs-3"></i>
                        </div>
                        <h4 class="text-white">Gestion Centralisée</h4>
                        <p class="text-light">Tous vos contenus au même endroit</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Fonctionnalités Principales</h2>
                <p class="lead">Découvrez les modules puissants de notre système</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mx-auto">
                                <i class="bi bi-file-text text-white fs-4"></i>
                            </div>
                            <h5 class="card-title">Gestion des Contenus</h5>
                            <p class="card-text">
                              <a href="{{ route('contenu.index') }}" class=" nav-link">   Créez, modifiez et organisez vos contenus culturels avec différents types de contenu.  </a>
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="bi bi-check text-success me-2"></i>Types de contenu personnalisables</li>
                                <li><i class="bi bi-check text-success me-2"></i>Édition en temps réel</li>
                                <li><i class="bi bi-check text-success me-2"></i>Gestion des statuts</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mx-auto">
                                <i class="bi bi-image text-white fs-4"></i>
                            </div>
                            <h5 class="card-title">Gestion des Médias</h5>
                            <p class="card-text">
                             <a href="{{ route('media.index') }}" class=" nav-link">    Uploader et organiser vos images, vidéos et documents avec différents types de médias.  </a>
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="bi bi-check text-success me-2"></i>Support multi-formats</li>
                                <li><i class="bi bi-check text-success me-2"></i>Catégorisation avancée</li>
                                <li><i class="bi bi-check text-success me-2"></i>Optimisation automatique</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mx-auto">
                                <i class="bi bi-globe text-white fs-4"></i>
                            </div>
                            <h5 class="card-title">Gestion Géographique</h5>
                            <p class="card-text">
                              <a href="{{ route('region.index') }}" class=" nav-link">  Organisez vos contenus par régions et localisations géographiques. </a>
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="bi bi-check text-success me-2"></i>Cartographie des régions</li>
                                <li><i class="bi bi-check text-success me-2"></i>Contenu localisé</li>
                                <li><i class="bi bi-check text-success me-2"></i>Gestion multi-régions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mx-auto">
                                <i class="bi bi-people text-white fs-4"></i>
                            </div>
                            <h5 class="card-title">Gestion des Utilisateurs</h5>
                            <p class="card-text">
                             <a href="{{ route('utilisateurs.index') }}" class=" nav-link">     Gérez les accès et permissions avec un système de rôles avancé.
                                  </a>
                            </p>
                          
                            <ul class="list-unstyled text-start"> 
                                <li><i class="bi bi-check text-success me-2"></i>Rôles personnalisables</li>
                                <li><i class="bi bi-check text-success me-2"></i>Gestion des permissions</li>
                                <li><i class="bi bi-check text-success me-2"></i>Profils utilisateurs</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mx-auto">
                                <i class="bi bi-chat-text text-white fs-4"></i>
                            </div>
                            <h5 class="card-title">Gestion des Commentaires</h5>
                            <p class="card-text">
                             <a href="{{ route('commentaire.index') }}" class=" nav-link">    Modérez et gérez les interactions avec votre audience. </a>
                            </p>
                        
                            <ul class="list-unstyled text-start">
                                <li><i class="bi bi-check text-success me-2"></i>Modération en temps réel</li>
                                <li><i class="bi bi-check text-success me-2"></i>Filtres automatiques</li>
                                <li><i class="bi bi-check text-success me-2"></i>Statistiques d'engagement</li>
                            </ul>
                        </div>
                    </div>
                </div>
               <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mx-auto">
                                <i class="bi bi-translate text-white fs-4"></i>
                            </div>
                            <h5 class="card-title">Multi-langues</h5>
                            <p class="card-text">
                              <a href="{{ route('langues.index') }}" class=" nav-link">   Support multi-langues pour une audience internationale.  </a> 
                            </p>
                            <ul class="list-unstyled text-start">
                                <li><i class="bi bi-check text-success me-2"></i>Interface traduite</li>
                                <li><i class="bi bi-check text-success me-2"></i>Contenu multi-langues</li>
                                <li><i class="bi bi-check text-success me-2"></i>Gestion facile des traductions</li>
                            </ul>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
   

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-3">Prêt à commencer ?</h2>
            <p class="lead mb-4">
                Rejoignez notre plateforme et transformez votre gestion de contenu culturel dès aujourd'hui.
            </p>
           
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-globe-europe-africa me-2"></i>Culture CMS</h5>
                    <p class="mb-0">Système de gestion de contenu culturel</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2024 Culture CMS. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>