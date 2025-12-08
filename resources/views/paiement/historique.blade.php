@extends('layouts.app')

@section('title', 'Mes Paiements')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-credit-card me-2"></i>
                            Mes Paiements
                        </h5>
                        <div class="text-end">
                            <small class="d-block">Total dépensé</small>
                            <strong class="fs-4">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</strong>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($paiements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Contenu</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Méthode</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paiements as $paiement)
                                        <tr>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $paiement->created_at->format('d/m/Y à H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($paiement->contenu)
                                                    <a href="{{ route('contenus.show.public', $paiement->contenu->id_contenu) }}" 
                                                       class="text-decoration-none">
                                                        {{ Str::limit($paiement->contenu->titre, 50) }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Contenu supprimé</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong class="text-primary">
                                                    {{ number_format($paiement->montant, 0, ',', ' ') }} {{ $paiement->devise }}
                                                </strong>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'paye' => 'success',
                                                        'en_attente' => 'warning',
                                                        'annule' => 'secondary',
                                                        'echec' => 'danger'
                                                    ];
                                                    $statusLabels = [
                                                        'paye' => 'Payé',
                                                        'en_attente' => 'En attente',
                                                        'annule' => 'Annulé',
                                                        'echec' => 'Échec'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$paiement->statut] ?? 'secondary' }}">
                                                    {{ $statusLabels[$paiement->statut] ?? $paiement->statut }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="bi bi-{{ $paiement->methode_paiement === 'stripe' ? 'credit-card' : 'cash' }} me-1"></i>
                                                    {{ ucfirst($paiement->methode_paiement) }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($paiement->contenu && $paiement->statut === 'paye')
                                                    <a href="{{ route('contenus.show.public', $paiement->contenu->id_contenu) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye me-1"></i>Voir
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $paiements->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h5 class="text-muted mt-3">Aucun paiement enregistré</h5>
                            <p class="text-muted">Vous n'avez effectué aucun achat pour le moment.</p>
                            <a href="{{ route('contenus.public') }}" class="btn btn-primary">
                                <i class="bi bi-compass me-2"></i>Explorer les contenus
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

