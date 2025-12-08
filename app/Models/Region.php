<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';
    protected $primaryKey = 'id_region';

    protected $fillable = [
        'nom_region',
        'description',
        'population',
        'superficie',
        'localisation'
    ];

    // Relation avec les contenus
    public function contenus()
    {
        return $this->hasMany(Contenu::class, 'id_region');
    }

    // Relation many-to-many avec les langues via la table parler
    public function langues()
    {
        return $this->belongsToMany(Langue::class, 'parler', 'id_region', 'id_langue');
    }

    // Relation directe avec la table parler
    public function parler()
    {
        return $this->hasMany(Parler::class, 'id_region');
    }

    // Scopes
    public function scopeAvecPopulation($query)
    {
        return $query->whereNotNull('population');
    }

    public function scopeAvecSuperficie($query)
    {
        return $query->whereNotNull('superficie');
    }

    // Accessors
    public function getDensiteAttribute()
    {
        if ($this->population && $this->superficie && $this->superficie > 0) {
            return round($this->population / $this->superficie, 2);
        }
        return null;
    }

    public function getPopulationFormateeAttribute()
    {
        return $this->population ? number_format($this->population, 0, ',', ' ') : null;
    }

    public function getSuperficieFormateeAttribute()
    {
        return $this->superficie ? number_format($this->superficie, 0, ',', ' ') : null;
    }

    public function getNombreContenusAttribute()
    {
        return $this->contenus()->count();
    }

    public function getNombreLanguesAttribute()
    {
        return $this->langues()->count();
    }
}