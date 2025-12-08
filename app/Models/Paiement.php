<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';
    protected $primaryKey = 'id_paiement';

    protected $fillable = [
        'id_utilisateur',
        'id_contenu',
        'montant',
        'devise',
        'statut',
        'methode_paiement',
        'transaction_id',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'montant' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    public function contenu()
    {
        return $this->belongsTo(Contenu::class, 'id_contenu');
    }

    public function estPaye()
    {
        return $this->statut === 'paye';
    }

    public function estEnAttente()
    {
        return $this->statut === 'en_attente';
    }

    public function marquerCommePaye()
    {
        $this->update(['statut' => 'paye']);
        return $this;
    }
}