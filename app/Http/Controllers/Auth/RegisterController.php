<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Role;
use App\Models\Langue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:utilisateurs,email'],
            'mot_de_passe' => ['required', 'string', 'min:8', 'confirmed'],
            'date_naissance' => ['required', 'date'],
            'sexe' => ['required', 'in:M,F'],
            'id_langue' => ['required', 'exists:langues,id_langue'],
        ]);
    }

    protected function create(array $data)
    {
        $roleUtilisateur = Role::where('nom_role', 'Utilisateur')->first();

        return Utilisateur::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'mot_de_passe' => Hash::make($data['mot_de_passe']),
            'date_naissance' => $data['date_naissance'],
            'sexe' => $data['sexe'],
            'id_langue' => $data['id_langue'],
            'id_role' => $roleUtilisateur->id_role,
            'date_inscription' => now(),
            'statut' => 'actif',
        ]);
    }

    public function showRegistrationForm()
    {
        $langues = Langue::all();
        return view('auth.register', compact('langues'));
    }
}