<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';
    protected $primaryKey = 'id_utilisateur';
     public $timestamps = false;
    protected $fillable = [
        'nom', 
        'email', 
        'mot_de_passe', 
        'prenom', 
        'sexe',
        'date_naissance', 
        'statut', 
        'photo', 
        'id_role', 
        'id_langue',
        'date_inscription'
    ];

    protected $hidden = [
        'mot_de_passe', 
        'remember_token',
    ];
    


    protected $casts = [
        'date_inscription' => 'datetime',
        'date_naissance' => 'date',
    ];

    // Relation avec le modèle Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id');
    }

    // Relation avec le modèle Langue
    public function langue()
    {
        return $this->belongsTo(Langue::class, 'id_langue');
    }

    // Relation avec les contenus créés
    public function contenus()
    {
        return $this->hasMany(Contenu::class, 'id_auteur');
    }

    // Relation avec les contenus modérés
    public function contenusModeres()
    {
        return $this->hasMany(Contenu::class, 'id_moderateur');
    }

    // Relation avec les commentaires
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'id_utilisateur');
    }

    // Méthodes utilitaires pour les rôles
    public function isAdmin()
    {
        return $this->role && $this->role->nom_role === 'Admin';
    }

    public function isModerator()
    {
        return $this->role && $this->role->nom_role === 'Moderateur';
    }

    public function isAuthor()
    {
        return $this->role && $this->role->nom_role === 'Auteur';
    }

    public function hasRole($role)
    {
        return $this->role && $this->role->nom_role === $role;
    }

    // Accessor pour le nom complet
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    // Accessor pour l'âge
    public function getAgeAttribute()
    {
        if (! $this->date_naissance) {
            return null;
        }

        // Supporte les strings ou les instances de date/Carbon
        return Carbon::parse($this->date_naissance)->age;
    }

    /**
     * Retourne le hash du mot de passe pour l'authentification Laravel.
     * Votre colonne s'appelle `mot_de_passe` au lieu de `password`.
     */
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    // Méthode pour vérifier si l'utilisateur est actif
    public function getEstActifAttribute()
    {
        return $this->statut === 'actif';
    }
}