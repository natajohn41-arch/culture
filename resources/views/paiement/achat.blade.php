@extends('layouts.public')

@section('title', 'Acheter : ' . $contenu->titre)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="bi bi-credit-card me-2"></i>
                            Acheter le contenu premium
                        </h4>
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-star-fill me-1"></i>PREMIUM
                        </span>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <!-- Aperçu du contenu -->
                    <div class="text-center mb-4">
                        <h2 class="text-dark mb-3">{{ $contenu->titre }}</h2>
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <span class="badge bg-info">{{ $contenu->langue->nom_langue ?? 'Non spécifié' }}</span>
                            <span class="badge bg-secondary">{{ $contenu->region->nom_region ?? 'Non spécifié' }}</span>
                        </div>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person-circle me-1"></i>
                            Par {{ $contenu->auteur->prenom ?? 'Auteur' }} {{ $contenu->auteur->nom ?? '' }}
                        </p>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="row">
                        <!-- Prix -->
                        <div class="col-md-6 mb-4">
                            <div class="pricing-card p-4 border rounded text-center h-100">
                                <h5 class="text-muted mb-3">Investissement</h5>
                                <div class="display-2 fw-bold text-primary my-3">
                                    {{ number_format($contenu->prix, 0, ',', ' ') }}
                                </div>
                                <div class="text-muted mb-3">Francs CFA</div>
                                <div class="small text-muted">
                                    <i class="bi bi-check-circle me-1"></i>Accès à vie<br>
                                    <i class="bi bi-check-circle me-1"></i>Support inclus
                                </div>
                            </div>
                        </div>
                        
                        <!-- Avantages -->
                        <div class="col-md-6 mb-4">
                            <div class="p-4 border rounded h-100">
                                <h5 class="mb-3 text-dark">
                                    <i class="bi bi-gift me-2"></i>Ce que vous obtenez :
                                </h5>
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>Accès complet</strong> au contenu
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>Téléchargement</strong> des médias associés
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>Soutien direct</strong> à l'auteur béninois
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>Accès permanent</strong> - Pas d'abonnement
                                    </li>
                                    <li>
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>Contribution</strong> à la culture béninoise
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Aperçu du contenu -->
                    <div class="mb-4">
                        <h5 class="mb-3 text-dark">
                            <i class="bi bi-eye me-2"></i>Aperçu du contenu
                        </h5>
                        <div class="preview-box p-3 border rounded bg-light" style="max-height: 200px; overflow-y: auto;">
                            {!! $contenu->apercu ?? Str::limit(strip_tags($contenu->texte), 300) . '...' !!}
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                C'est juste un aperçu. Le contenu complet fait {{ Str::length(strip_tags($contenu->texte)) }} caractères.
                            </small>
                        </div>
                    </div>
                    
                    <!-- Bouton de paiement -->
                    <div class="text-center mt-5">
                        <form action="{{ route('contenus.paiement.process', $contenu->id_contenu) }}" method="POST" id="paiementForm">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3" id="btnPayer">
                                <i class="bi bi-credit-card me-2"></i>
                                Payer {{ number_format($contenu->prix, 0, ',', ' ') }} FCFA
                            </button>
                        </form>
                        
                        <!-- Mode test (développement seulement) -->
                        @if(app()->environment('local'))
                        <div class="mt-3">
                            <a href="{{ route('paiement.test', $contenu->id_contenu) }}" 
                               class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-flask me-1"></i>
                                Tester sans payer (DEV)
                            </a>
                        </div>
                        @endif
                        
                        <!-- Sécurité -->
                        <div class="mt-4">
                            <div class="d-flex justify-content-center align-items-center text-muted">
                                <i class="bi bi-shield-check text-success me-2"></i>
                                <small>Paiement 100% sécurisé par</small>
                                <img src="https://stripe.com/img/v3/home/twitter.png" alt="Stripe" height="20" class="ms-2">
                            </div>
                        </div>
                        
                        <!-- Retour -->
                        <div class="mt-4">
                            <a href="{{ route('contenus.show.public', $contenu->id_contenu) }}" 
                               class="text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i>
                                Retour au contenu
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-light py-3">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <small class="text-muted">
                                <i class="bi bi-currency-exchange me-1"></i>
                                80% revient à l'auteur
                            </small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">
                                <i class="bi bi-clock-history me-1"></i>
                                Accès immédiat après paiement
                            </small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">
                                <i class="bi bi-headset me-1"></i>
                                Support 7j/7
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pricing-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transition: transform 0.3s;
}
.pricing-card:hover {
    transform: translateY(-5px);
}
.preview-box {
    font-size: 0.95rem;
    line-height: 1.6;
}
#btnPayer {
    min-width: 250px;
    transition: all 0.3s;
}
#btnPayer:hover {
    transform: scale(1.05);
}
</style>

<script>
document.getElementById('paiementForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('btnPayer');
    btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Redirection vers Stripe...';
    btn.disabled = true;
    btn.classList.add('disabled');
});
</script>
@endsection