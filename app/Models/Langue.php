<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Langue extends Model
{
    use HasFactory;

    protected $table = 'langues';
    protected $primaryKey = 'id_langue';

    protected $fillable = [
        'nom_langue',
        'code_langue',
        'description'
    ];

    // Relation avec les contenus
    public function contenus()
    {
        return $this->hasMany(Contenu::class, 'id_langue');
    }

    // Relation avec les utilisateurs
    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class, 'id_langue');
    }

    // Relation many-to-many avec les régions via la table parler
    public function regions()
    {
        return $this->belongsToMany(Region::class, 'parler', 'id_langue', 'id_region');
    }

    // Relation directe avec la table parler
    public function parler()
    {
        return $this->hasMany(Parler::class, 'id_langue');
    }

    // Scopes
    public function scopeAvecCode($query, $code)
    {
        return $query->where('code_langue', $code);
    }

    // Accessors
    public function getEstUtiliseeAttribute()
    {
        return $this->contenus()->count() > 0 || $this->utilisateurs()->count() > 0;
    }

    public function getNombreContenusAttribute()
    {
        return $this->contenus()->count();
    }

    public function getNombreUtilisateursAttribute()
    {
        return $this->utilisateurs()->count();
    }

    public function getNombreRegionsAttribute()
    {
        return $this->regions()->count();
    }
}