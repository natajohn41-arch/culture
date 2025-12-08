@extends('layouts.public')

@section('title', 'À Propos - Culture Bénin')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">À Propos de Culture Bénin</h1>
                <p class="lead mb-4">Découvrez notre mission de préservation et de promotion de la riche culture béninoise</p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="bi bi-globe-europe-africa display-1"></i>
            </div>
        </div>
    </div>
</section>

<!-- Notre Mission -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold mb-4">Notre Mission</h2>
                <p class="lead text-muted max-w-800 mx-auto">
                    Culture Bénin est une plateforme dédiée à la préservation, la promotion 
                    et la célébration de la diversité culturelle du Bénin à travers ses 
                    langues, ses traditions, son artisanat et son patrimoine.
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <i class="bi bi-translate display-4 text-primary mb-3"></i>
                        <h5 class="card-title">Préservation Linguistique</h5>
                        <p class="card-text text-muted">
                            Documenter et promouvoir les langues locales du Bénin pour 
                            assurer leur transmission aux générations futures.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <i class="bi bi-people display-4 text-success mb-3"></i>
                        <h5 class="card-title">Partage Culturel</h5>
                        <p class="card-text text-muted">
                            Créer un espace d'échange où les communautés peuvent partager 
                            leurs connaissances et traditions culturelles.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <i class="bi bi-book display-4 text-warning mb-3"></i>
                        <h5 class="card-title">Éducation</h5>
                        <p class="card-text text-muted">
                            Sensibiliser le public à l'importance du patrimoine culturel 
                            béninois et à sa diversité régionale.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nos Valeurs -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold mb-4">Nos Valeurs</h2>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-heart-fill text-danger fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5>Authenticité</h5>
                        <p class="text-muted mb-0">Respect et fidélité aux traditions et pratiques culturelles authentiques.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-share-fill text-primary fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5>Partage</h5>
                        <p class="text-muted mb-0">Promouvoir l'échange et la transmission des connaissances culturelles.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-globe2 text-success fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5>Inclusion</h5>
                        <p class="text-muted mb-0">Valoriser toutes les cultures et communautés du Bénin sans exclusion.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-lightbulb-fill text-warning fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5>Innovation</h5>
                        <p class="text-muted mb-0">Utiliser la technologie pour préserver et diffuser le patrimoine culturel.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistiques Dynamiques -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-primary text-white">
                    <div class="card-body py-4">
                        <h3 class="fw-bold display-6">{{ $totalRegions }}</h3>
                        <p class="mb-0">Régions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-success text-white">
                    <div class="card-body py-4">
                        <h3 class="fw-bold display-6">{{ $totalLangues }}</h3>
                        <p class="mb-0">Langues</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-warning text-white">
                    <div class="card-body py-4">
                        <h3 class="fw-bold display-6">{{ $totalContenus }}</h3>
                        <p class="mb-0">Contenus</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-info text-white">
                    <div class="card-body py-4">
                        <h3 class="fw-bold display-6">{{ $totalUtilisateurs }}</h3>
                        <p class="mb-0">Membres</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Équipe -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold mb-4">Notre Équipe</h2>
                <p class="lead text-muted">Des passionnés dévoués à la culture béninoise</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mx-auto mb-3 bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-person-fill fs-3"></i>
                        </div>
                        <h5 class="card-title">Équipe Culture Bénin</h5>
                        <p class="card-text text-muted">
                            Une équipe multidisciplinaire de chercheurs, développeurs 
                            et passionnés de culture travaillant ensemble pour préserver 
                            le patrimoine béninois.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">Rejoignez Notre Communauté</h2>
        <p class="lead mb-4">Contribuez à la préservation et à la promotion de la culture béninoise</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            @auth
                <a href="{{ route('contenus.public') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-compass me-2"></i>Explorer les Contenus
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-person-plus me-2"></i>S'inscrire
                </a>
                <a href="{{ route('contenus.public') }}" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-compass me-2"></i>Explorer
                </a>
            @endauth
        </div>
    </div>
</section>

<style>
    .max-w-800 {
        max-width: 800px;
    }
    
    .card {
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection