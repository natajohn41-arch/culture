<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Role;
use App\Models\Langue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Nettoyer l'email (supprimer les espaces)
        $email = trim($credentials['email']);
        $password = $credentials['password'];

        // Vérifier si l'utilisateur existe
        $user = Utilisateur::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Compte inexistant ou désactivé.',
            ])->onlyInput('email');
        }

        // Vérifier le statut
        if ($user->statut !== 'actif') {
            return back()->withErrors([
                'email' => 'Compte inexistant ou désactivé.',
            ])->onlyInput('email');
        }

        // Vérifier que le mot de passe hashé existe
        if (empty($user->mot_de_passe)) {
            return back()->withErrors([
                'email' => 'Erreur de configuration du compte. Veuillez contacter l\'administrateur.',
            ])->onlyInput('email');
        }

        // Vérifier le mot de passe avec Hash::check
        // Note: On ne peut pas utiliser Auth::attempt() directement car Laravel cherche 
        // un champ 'password' dans la table, mais nous avons 'mot_de_passe'
        $passwordValid = Hash::check($password, $user->mot_de_passe);
        
        if (!$passwordValid) {
            // En mode debug, logger pour diagnostiquer
            if (config('app.debug')) {
                \Log::debug('Login failed', [
                    'email' => $email,
                    'user_exists' => true,
                    'user_status' => $user->statut,
                    'has_password_hash' => !empty($user->mot_de_passe),
                    'password_length' => strlen($password),
                    'hash_prefix' => substr($user->mot_de_passe, 0, 10),
                ]);
            }
            
            return back()->withErrors([
                'email' => 'Les identifiants sont incorrects. Vérifiez votre email et mot de passe.',
            ])->onlyInput('email');
        }

        // Connecter l'utilisateur
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        
        return redirect()->intended('dashboard');
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegister()
    {
        $langues = Langue::all();
        return view('auth.register', compact('langues'));
    }

    /**
     * Traiter l'inscription
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'password' => 'required|string|min:8|confirmed',
            'date_naissance' => 'required|date|before:-18 years',
            'sexe' => 'required|in:M,F',
            'id_langue' => 'required|exists:langues,id_langue',
        ], [
            'date_naissance.before' => 'Vous devez avoir au moins 18 ans pour vous inscrire.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Récupérer le rôle "Utilisateur" par défaut
        $roleUtilisateur = Role::where('nom_role', 'Utilisateur')->first();

        if (!$roleUtilisateur) {
            return redirect()->back()
                ->withErrors(['error' => 'Erreur de configuration des rôles.'])
                ->withInput();
        }

        // Créer l'utilisateur
        $user = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->password),
            'date_naissance' => $request->date_naissance,
            'sexe' => $request->sexe,
            'id_langue' => $request->id_langue,
            'id_role' => $roleUtilisateur->id,
            'date_inscription' => now(),
            'statut' => 'actif',
            'photo' => 'default.png',
        ]);

        // Connecter automatiquement l'utilisateur
        Auth::login($user);
        $request->session()->regenerate();

        // Redirection avec pattern POST-REDIRECT-GET pour éviter l'avertissement du navigateur
        return redirect()->intended('dashboard')
            ->with('success', 'Inscription réussie ! Bienvenue sur Culture Bénin.');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}