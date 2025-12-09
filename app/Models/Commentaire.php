<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;

    protected $table = 'commentaires';
    protected $primaryKey = 'id_commentaire';

    protected $fillable = [
        'texte',
        'note',
        'date',
        'id_utilisateur',
        'id_contenu'
    ];

    protected $casts = [
        'date' => 'datetime',
        'note' => 'integer',
    ];

    // Relation avec l'utilisateur
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    // Relation avec le contenu
    public function contenu()
    {
        return $this->belongsTo(Contenu::class, 'id_contenu');
    }

    // Scopes
    public function scopeAvecNote($query)
    {
        return $query->whereNotNull('note');
    }

    public function scopeSansNote($query)
    {
        return $query->whereNull('note');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('date', 'desc');
    }

    // Accessors
    public function getANoteAttribute()
    {
        return !is_null($this->note);
    }

    public function getNoteSurCinqAttribute()
    {
        return $this->note ? $this->note . '/5' : null;
    }

    // Méthode pour obtenir un extrait du commentaire
    public function getExtraitAttribute()
    {
        return \Str::limit($this->texte, 100);
    }
}