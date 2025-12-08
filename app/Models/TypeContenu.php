<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TypeContenu extends Model
{
    use HasFactory;

    protected $table = 'type_contenus';
    protected $primaryKey = 'id_type_contenu';
public $timestamps = false;
    protected $fillable = [
        'nom_contenu',
        'description'
    ];

    // Relation avec les contenus
    public function contenus()
    {
        return $this->hasMany(Contenu::class, 'id_type_contenu');
    }

    // Accessors
    public function getNombreContenusAttribute()
    {
        return $this->contenus()->count();
    }

    public function getEstUtiliseAttribute()
    {
        return $this->contenus()->count() > 0;
    }

    /**
     * Accessor pour convertir created_at en Carbon si c'est une chaîne
     * Retourne null si la valeur est null ou vide
     */
    public function getCreatedAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        if (is_string($value)) {
            return Carbon::parse($value);
        }
        return $value;
    }

    /**
     * Accessor pour convertir updated_at en Carbon si c'est une chaîne
     * Retourne null si la valeur est null ou vide
     */
    public function getUpdatedAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        if (is_string($value)) {
            return Carbon::parse($value);
        }
        return $value;
    }
}