<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';
    protected $primaryKey = 'id_utilisateur';

    protected $fillable = [
        'nom', 'email', 'mot_de_passe', 'prenom', 'sexe',
        'date_naissance', 'statut', 'photo', 'id_role', 'id_langue'
    ];

    protected $hidden = [
        'mot_de_passe', 'remember_token',
    ];

    protected $casts = [
        'date_inscription' => 'datetime',
        'date_naissance' => 'date',
    ];

    // Relations
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id');
    }

    public function langue()
    {
        return $this->belongsTo(Langue::class, 'id_langue');
    }

    public function contenus()
    {
        return $this->hasMany(Contenu::class, 'id_auteur');
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'id_utilisateur');
    }

    // Méthodes utilitaires pour les rôles
    public function isAdmin()
    {
        return $this->role->nom_role === 'Admin';
    }

    public function isModerator()
    {
        return $this->role->nom_role === 'Moderateur';
    }

    public function isAuthor()
    {
        return $this->role->nom_role === 'Auteur';
    }

    public function hasRole($role)
    {
        return $this->role->nom_role === $role;
    }
}