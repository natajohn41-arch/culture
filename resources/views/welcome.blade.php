<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Culture - Système de Gestion de Contenu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-glass sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
                <span class="stat-chip">
                    <i class="bi bi-globe-europe-africa text-primary"></i>
                    Bénin
                </span>
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
    <section class="hero-modern py-5">
        <div class="hero-pattern"></div>
        <div class="container py-5">
            <div class="row align-items-center gy-4">
                <div class="col-lg-7 animate-fade-up">
                    <span class="badge-heritage mb-3">
                        <i class="bi bi-star-fill"></i> Patrimoine & langues du Bénin
                    </span>
                    <h1 class="display-5 fw-bold mb-3 text-white">
                        Gérez et valorisez vos contenus culturels avec élégance
                    </h1>
                    <p class="lead text-white-50 mb-4">
                        Une plateforme complète pour publier, organiser et diffuser les richesses culturelles, les langues et les régions du Bénin, avec une expérience fluide et moderne.
                    </p>
                    <div class="hero-cta">
                        <a href="{{ route('login') }}" class="btn btn-culture btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Accéder à l’espace
                        </a>
                        <a href="#features" class="btn btn-outline-culture btn-lg">
                            <i class="bi bi-info-circle me-2"></i>Découvrir les modules
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 text-lg-end text-center animate-fade-right">
                    <div class="card border-0 card-heritage p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="icon-badge"><i class="bi bi-journal-text"></i></span>
                            <div>
                                <h5 class="mb-0">Gestion centralisée</h5>
                                <small class="text-muted">Contenus, médias, régions, rôles</small>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">
                            Publiez, modérez et mettez en valeur vos articles, histoires, images et langues depuis un tableau de bord clair.
                        </p>
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
                    <div class="card card-heritage h-100 border-0 p-4 hover-lift animate-fade-up">
                        <div class="text-center">
                            <div class="icon-badge mb-3 mx-auto">
                                <i class="bi bi-file-text"></i>
                            </div>
                            <h5 class="card-title">Gestion des Contenus</h5>
                            <p class="text-muted-strong">
                                Créez, modifiez et organisez vos contenus culturels par type.
                            </p>
                        </div>
                        <ul class="list-unstyled text-start small mb-0">
                            <li><i class="bi bi-check text-success me-2"></i>Types de contenu personnalisables</li>
                            <li><i class="bi bi-check text-success me-2"></i>Édition rapide et statuts</li>
                            <li><i class="bi bi-check text-success me-2"></i>Publication validée</li>
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('contenus.index') }}" class="btn btn-outline-culture w-100">Voir les contenus</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-heritage h-100 border-0 p-4 hover-lift animate-fade-up">
                        <div class="text-center">
                            <div class="icon-badge mb-3 mx-auto">
                                <i class="bi bi-image"></i>
                            </div>
                            <h5 class="card-title">Gestion des Médias</h5>
                            <p class="text-muted-strong">
                                Uploadez et organisez images, vidéos et documents par catégories.
                            </p>
                        </div>
                        <ul class="list-unstyled text-start small mb-0">
                            <li><i class="bi bi-check text-success me-2"></i>Support multi-formats</li>
                            <li><i class="bi bi-check text-success me-2"></i>Catégorisation avancée</li>
                            <li><i class="bi bi-check text-success me-2"></i>Optimisation automatique</li>
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('media.index') }}" class="btn btn-outline-culture w-100">Voir les médias</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-heritage h-100 border-0 p-4 hover-lift animate-fade-up">
                        <div class="text-center">
                            <div class="icon-badge mb-3 mx-auto">
                                <i class="bi bi-globe"></i>
                            </div>
                            <h5 class="card-title">Gestion Géographique</h5>
                            <p class="text-muted-strong">
                                Organisez vos contenus par régions et localisations.
                            </p>
                        </div>
                        <ul class="list-unstyled text-start small mb-0">
                            <li><i class="bi bi-check text-success me-2"></i>Cartographie des régions</li>
                            <li><i class="bi bi-check text-success me-2"></i>Contenu localisé</li>
                            <li><i class="bi bi-check text-success me-2"></i>Gestion multi-régions</li>
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('regions.index') }}" class="btn btn-outline-culture w-100">Voir les régions</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="card card-heritage h-100 border-0 p-4 hover-lift animate-fade-up">
                        <div class="text-center">
                            <div class="icon-badge mb-3 mx-auto">
                                <i class="bi bi-people"></i>
                            </div>
                            <h5 class="card-title">Gestion des Utilisateurs</h5>
                            <p class="text-muted-strong">Rôles, permissions et profils complets.</p>
                        </div>
                        <ul class="list-unstyled text-start small mb-0">
                            <li><i class="bi bi-check text-success me-2"></i>Rôles personnalisables</li>
                            <li><i class="bi bi-check text-success me-2"></i>Permissions fines</li>
                            <li><i class="bi bi-check text-success me-2"></i>Profils détaillés</li>
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('utilisateurs.index') }}" class="btn btn-outline-culture w-100">Voir les utilisateurs</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-heritage h-100 border-0 p-4 hover-lift animate-fade-up">
                        <div class="text-center">
                            <div class="icon-badge mb-3 mx-auto">
                                <i class="bi bi-chat-text"></i>
                            </div>
                            <h5 class="card-title">Gestion des Commentaires</h5>
                            <p class="text-muted-strong">Modération et suivi de l’engagement.</p>
                        </div>
                        <ul class="list-unstyled text-start small mb-0">
                            <li><i class="bi bi-check text-success me-2"></i>Modération en temps réel</li>
                            <li><i class="bi bi-check text-success me-2"></i>Filtres automatiques</li>
                            <li><i class="bi bi-check text-success me-2"></i>Statistiques d'engagement</li>
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('commentaire.index') }}" class="btn btn-outline-culture w-100">Voir les commentaires</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-heritage h-100 border-0 p-4 hover-lift animate-fade-up">
                        <div class="text-center">
                            <div class="icon-badge mb-3 mx-auto">
                                <i class="bi bi-translate"></i>
                            </div>
                            <h5 class="card-title">Multi-langues</h5>
                            <p class="text-muted-strong">Interface et contenus traduits.</p>
                        </div>
                        <ul class="list-unstyled text-start small mb-0">
                            <li><i class="bi bi-check text-success me-2"></i>Interface traduite</li>
                            <li><i class="bi bi-check text-success me-2"></i>Contenus multi-langues</li>
                            <li><i class="bi bi-check text-success me-2"></i>Gestion simple des traductions</li>
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('langues.index') }}" class="btn btn-outline-culture w-100">Voir les langues</a>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </section>


    <!-- Regions horizontal section -->
    @php
        use App\Models\Region;
        use App\Models\Contenu;
        $regions = Region::orderBy('nom_region')->get();
        $contents = Contenu::where('statut', 'valide')->orderBy('created_at', 'desc')->take(12)->get();
    @endphp

    <section id="regions" class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold section-title">Régions</h3>
                <small class="text-muted">Parcourez les départements</small>
            </div>
            <div class="scroll-wrapper">
                <div class="scroll-container" id="regions-scroll">
                    @foreach($regions as $region)
                        <div class="scroll-item">
                            <div class="mb-2">
                                <img src="https://picsum.photos/seed/region-{{ $region->id_region ?? $region->id }}/600/300" alt="{{ $region->nom_region }}">
                            </div>
                            <h5 class="mb-1">{{ $region->nom_region }}</h5>
                            <p class="mb-1 text-muted small">{{ $region->localisation ?? '' }}</p>
                            <p class="mb-0 small">{{ Str::limit($region->description ?? '', 120) }}</p>
                            <div class="mt-2 small text-muted">
                                Population: {{ number_format($region->population ?? 0, 0, ',', ' ') }} • Superficie: {{ number_format($region->superficie ?? 0, 0, ',', ' ') }} km²
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="scroll-controls">
                    <button class="btn btn-sm btn-light btn-left" data-target="#regions-scroll" data-dir="-1"><i class="bi bi-chevron-left"></i></button>
                    <button class="btn btn-sm btn-light btn-right" data-target="#regions-scroll" data-dir="1"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </section>

    <!-- Contents horizontal section -->
    <section id="contenus" class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold section-title">Contenus publiés</h3>
                <small class="text-muted">Dernières publications</small>
            </div>
            <div class="scroll-wrapper">
                <div class="scroll-container" id="contents-scroll">
                    @foreach($contents as $c)
                        <div class="scroll-item">
                            <div class="mb-2">
                                <img src="https://picsum.photos/seed/contenu-{{ $c->id }}/600/300" alt="{{ $c->titre ?? 'Contenu' }}">
                            </div>
                            <h5 class="mb-1">{{ $c->titre ?? 'Sans titre' }}</h5>
                            <p class="mb-1 text-muted small">Type: {{ $c->type_contenu_id ?? '—' }} • Région: {{ optional($c->region)->nom_region ?? '—' }}</p>
                            <p class="mb-0 small">{{ Str::limit(strip_tags($c->texte ?? $c->description ?? ''), 120) }}</p>
                            <div class="mt-2 small text-muted">Publié: {{ $c->created_at?->format('d/m/Y') ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="scroll-controls">
                    <button class="btn btn-sm btn-light btn-left" data-target="#contents-scroll" data-dir="-1"><i class="bi bi-chevron-left"></i></button>
                    <button class="btn btn-sm btn-light btn-right" data-target="#contents-scroll" data-dir="1"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #1f3b4d, #f27f3d);">
        <div class="container text-center text-white">
            <h2 class="fw-bold mb-3">Prêt à commencer ?</h2>
            <p class="lead mb-4">
                Rejoignez notre plateforme et valorisez vos contenus culturels dès aujourd'hui.
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('register') }}" class="btn btn-light btn-lg text-primary fw-bold">
                    <i class="bi bi-person-plus me-2"></i>Créer un compte
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-4">
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
    <script>
        // Helpers for horizontal scroll controls and auto-scroll
        (function(){
            const SCROLL_STEP = 300; // pixels per button click
            const AUTO_DELAY = 3000; // ms between auto scrolls
            const autoTimers = new Map();

            function setupScrollArea(containerId){
                const container = document.querySelector(containerId);
                if(!container) return;

                // Buttons
                const wrapper = container.closest('.scroll-wrapper');
                const left = wrapper.querySelector('.btn-left');
                const right = wrapper.querySelector('.btn-right');

                left.addEventListener('click', () => container.scrollBy({ left: -SCROLL_STEP, behavior: 'smooth' }));
                right.addEventListener('click', () => container.scrollBy({ left: SCROLL_STEP, behavior: 'smooth' }));

                // Auto-scroll
                function startAuto(){
                    stopAuto();
                    const t = setInterval(()=>{ container.scrollBy({ left: SCROLL_STEP, behavior: 'smooth' }); }, AUTO_DELAY);
                    autoTimers.set(containerId, t);
                }
                function stopAuto(){
                    const t = autoTimers.get(containerId);
                    if(t){ clearInterval(t); autoTimers.delete(containerId); }
                }

                container.addEventListener('mouseenter', stopAuto);
                container.addEventListener('mouseleave', startAuto);
                startAuto();
            }

            document.addEventListener('DOMContentLoaded', function(){
                setupScrollArea('#regions-scroll');
                setupScrollArea('#contents-scroll');
            });
        })();
    </script>
</body>
</html>