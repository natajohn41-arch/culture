@extends('layout')

@section('Content')

{{-- CONTENU PRINCIPAL --}}
<div class="card shadow-lg border-0 mb-4">
    <div class="card-header bg-primary text-white py-3">
        <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> {{ $contenu->titre }}</h4>
    </div>

    <div class="card-body p-4">

        <p><strong>Statut :</strong>
            <span class="badge {{ $contenu->statut == 'Bon' ? 'bg-success' : 'bg-danger' }}">
                {{ $contenu->statut }}
            </span>
        </p>

        <p><strong>Date de création :</strong> {{ $contenu->date_creation }}</p>

        <hr>

        <p class="fw-bold mb-1">Texte :</p>

        <div class="p-3 bg-light rounded shadow-sm">
            {!! nl2br(e($contenu->texte)) !!}
        </div>

        <a href="{{ route('contenus.index') }}" class="btn btn-secondary mt-4">
            Retour à la liste
        </a>
    </div>
</div>

{{-- COMMENTAIRES --}}
<div class="card shadow-lg border-0 mb-4">
    <div class="card-header bg-success text-white py-3">
        <h4 class="mb-0"><i class="bi bi-chat-left-text"></i> Commentaires associés ({{ $contenu->commentaires->count() }})</h4>
    </div>

    <div class="card-body p-4">

        @forelse($contenu->commentaires as $commentaire)

            <div class="p-3 mb-3 bg-white border rounded shadow-sm">

                <p class="fw-bold mb-1">
                    <i class="bi bi-person-circle"></i> 
                    {{ $commentaire->utilisateur->nom }} {{ $commentaire->utilisateur->prenom }}
                    <small class="text-muted">— {{ $commentaire->date }}</small>
                </p>

                <p class="text-warning mb-2" style="font-size:1.3rem;">
                    {!! str_repeat('★', $commentaire->note) !!}
                    {!! str_repeat('☆', 5 - $commentaire->note) !!}
                </p>

                <p class="mb-0 text-secondary">
                    {{ $commentaire->texte }}
                </p>

            </div>

        @empty

            <p class="text-muted fst-italic">
                Aucun commentaire pour ce contenu.
            </p>

        @endforelse

    </div>
</div>

@endsection
