<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeMedia extends Model
{
    use HasFactory;

    protected $table = 'type_medias';
    protected $primaryKey = 'id_type_media';

    protected $fillable = [
        'nom_media',
        'description',
        'extensions'
    ];

    // Relation avec les médias
    public function medias()
    {
        return $this->hasMany(Media::class, 'id_type_media');
    }

    // Accessors
    public function getListeExtensionsAttribute()
    {
        return $this->extensions ? explode(',', $this->extensions) : [];
    }

    public function getNombreMediasAttribute()
    {
        return $this->medias()->count();
    }

    public function getEstUtiliseAttribute()
    {
        return $this->medias()->count() > 0;
    }
}