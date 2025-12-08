<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}