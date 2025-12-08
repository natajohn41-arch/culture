<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parler extends Model
{
    use HasFactory;

    protected $table = 'parler';
    
    // Pas de primary key car c'est une table de liaison
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_region',
        'id_langue'
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
}