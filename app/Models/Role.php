<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';
    // La table `role` utilise la colonne `id` (créée par $table->id() dans la migration).
    // L'application a parfois attendu `id_role`, on expose un accessor pour compatibilité.
    protected $primaryKey = 'id';

    protected $fillable = [
        'nom_role',
        'description'
    ];

    // Relation avec les utilisateurs
    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class, 'id_role', 'id');
    }

    // Compatibilité : certaines parties du code utilisent `$role->id_role`.
    public function getIdRoleAttribute()
    {
        return $this->attributes['id'] ?? null;
    }

    // Scopes
    public function scopeSysteme($query)
    {
        return $query->whereIn('nom_role', ['Admin', 'Moderateur', 'Auteur', 'Utilisateur']);
    }

    public function scopePersonnalise($query)
    {
        return $query->whereNotIn('nom_role', ['Admin', 'Moderateur', 'Auteur', 'Utilisateur']);
    }

    // Accessors
    public function getEstSystemeAttribute()
    {
        return in_array($this->nom_role, ['Admin', 'Moderateur', 'Auteur', 'Utilisateur']);
    }

    public function getEstPersonnaliseAttribute()
    {
        return !$this->est_systeme;
    }

    public function getNombreUtilisateursAttribute()
    {
        return $this->utilisateurs()->count();
    }

    // Méthodes pour vérifier le type de rôle
    public function isAdmin()
    {
        return $this->nom_role === 'Admin';
    }

    public function isModerator()
    {
        return $this->nom_role === 'Moderateur';
    }

    public function isAuthor()
    {
        return $this->nom_role === 'Auteur';
    }

    public function isUser()
    {
        return $this->nom_role === 'Utilisateur';
    }
}