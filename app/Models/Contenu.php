<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contenu extends Model
{
    use HasFactory;

    protected $table = 'contenus';
    protected $primaryKey = 'id_contenu';

    protected $fillable = [
        'titre', 
        'texte', 
        'date_creation', 
        'statut', 
        'parent_id',
        'date_validation', 
        'id_region', 
        'id_langue', 
        'id_moderateur',
        'id_type_contenu', 
        'id_auteur',
        'est_premium',
        'prix'
    ];

    protected $casts = [
        'date_creation' => 'datetime',
        'date_validation' => 'datetime',
        'est_premium' => 'boolean',
        'prix' => 'decimal:2',
    ];

    // Relation avec la région
    public function region()
    {
        return $this->belongsTo(Region::class, 'id_region');
    }

    // Relation avec la langue
    public function langue()
    {
        return $this->belongsTo(Langue::class, 'id_langue');
    }

    // Relation avec l'auteur
    public function auteur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_auteur');
    }

    // Relation avec le modérateur
    public function moderateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_moderateur');
    }

    // Relation avec le type de contenu
    public function typeContenu()
    {
        return $this->belongsTo(TypeContenu::class, 'id_type_contenu');
    }

    // Relation avec les médias
    public function medias()
    {
        return $this->hasMany(Media::class, 'id_contenu');
    }

    // Relation avec les commentaires
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'id_contenu');
    }

    // Relation récursive pour les contenus parents/enfants
    public function parent()
    {
        return $this->belongsTo(Contenu::class, 'parent_id');
    }

    public function enfants()
    {
        return $this->hasMany(Contenu::class, 'parent_id');
    }

    // Scopes pour filtrer les contenus
    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeRejete($query)
    {
        return $query->where('statut', 'rejete');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('date_creation', 'desc');
    }

    // Accessors
    public function getEstValideAttribute()
    {
        return $this->statut === 'valide';
    }

    public function getEstEnAttenteAttribute()
    {
        return $this->statut === 'en_attente';
    }

    public function getEstRejeteAttribute()
    {
        return $this->statut === 'rejete';
    }

    public function getNombreCommentairesAttribute()
    {
        return $this->commentaires()->count();
    }

    public function getNombreMediasAttribute()
    {
        return $this->medias()->count();
    }

    // Méthode pour obtenir un extrait du texte
    public function getExtraitAttribute()
    {
        return \Str::limit(strip_tags($this->texte), 150);
    }
}