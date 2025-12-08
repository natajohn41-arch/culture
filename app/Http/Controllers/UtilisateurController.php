<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use App\Models\Role;
use App\Models\Langue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UtilisateurController extends Controller
{
    /**
     * Display a listing of the resource (Admin seulement).
     */
    public function index()
    {
        // Vérifier les permissions
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $utilisateurs = Utilisateur::with(['role', 'langue'])
            ->orderBy('id_utilisateur', 'desc')
            ->get();
            
        return view('utilisateurs.index', compact('utilisateurs'));
    }

    /**
     * Show the form for creating a new resource (Admin seulement).
     */
    public function create()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $roles = Role::all();
        $langues = Langue::all();
        return view('utilisateurs.create', compact('roles', 'langues'));
    }

    /**
     * Store a newly created resource in storage (Admin seulement).
     */
    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'mot_de_passe' => 'required|string|min:8|confirmed',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'id_langue' => 'required|exists:langues,id_langue',
            'id_role' => 'required|exists:role,id',
            'statut' => 'required|in:actif,inactif',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gérer l'upload de la photo
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos/utilisateurs', 'public');
        }

        // Créer l'utilisateur
        Utilisateur::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'mot_de_passe' => Hash::make($data['mot_de_passe']),
            'date_naissance' => $data['date_naissance'],
            'sexe' => $data['sexe'],
            'id_langue' => $data['id_langue'],
            'id_role' => $data['id_role'],
            'statut' => $data['statut'],
            'photo' => $photoPath,
            'date_inscription' => now(),
            'remember_token' => null,
        ]);

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $utilisateur = Utilisateur::with(['role', 'langue'])->findOrFail($id);
        
        // Vérifier les permissions (propre profil ou admin)
        if (Auth::id() !== $utilisateur->id_utilisateur && !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        return view('utilisateurs.show', compact('utilisateur'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        
        // Vérifier les permissions (propre profil ou admin)
        if (Auth::id() !== $utilisateur->id_utilisateur && !Auth::user()->isAdmin()) {
            abort(403, 'Vous ne pouvez modifier que votre propre profil.');
        }

        $roles = Role::all();
        $langues = Langue::all();
        
        return view('utilisateurs.edit', compact('utilisateur', 'roles', 'langues'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $utilisateur = Utilisateur::findOrFail($id);

        // Vérifier les permissions (propre profil ou admin)
        if (Auth::id() !== $utilisateur->id_utilisateur && !Auth::user()->isAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $utilisateur->id_utilisateur . ',id_utilisateur',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'id_langue' => 'required|exists:langues,id_langue',
            'id_role' => 'required|exists:role,id',
            'statut' => 'required|in:actif,inactif',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gérer l'upload de la photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($utilisateur->photo) {
                Storage::disk('public')->delete($utilisateur->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos/utilisateurs', 'public');
        }

        // Mettre à jour l'utilisateur
        $utilisateur->update($data);

        $redirectRoute = Auth::user()->isAdmin() ? 'utilisateurs.index' : 'profile.edit';
        
        return redirect()->route($redirectRoute)
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage (Admin seulement).
     */
    public function destroy(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Action réservée aux administrateurs.');
        }

        // Empêcher la suppression de son propre compte
        if (Auth::id() == $id) {
            return redirect()->route('utilisateurs.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $utilisateur = Utilisateur::findOrFail($id);
        
        // Supprimer la photo si elle existe
        if ($utilisateur->photo) {
            Storage::disk('public')->delete($utilisateur->photo);
        }

        $utilisateur->delete();

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Activer/Désactiver un utilisateur (Admin seulement).
     */
    public function toggleStatus($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Action réservée aux administrateurs.');
        }

        $utilisateur = Utilisateur::findOrFail($id);
        $utilisateur->statut = $utilisateur->statut === 'actif' ? 'inactif' : 'actif';
        $utilisateur->save();

        $message = $utilisateur->statut === 'actif' ? 'Utilisateur activé.' : 'Utilisateur désactivé.';

        return back()->with('success', $message);
    }
}