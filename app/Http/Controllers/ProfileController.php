<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Langue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Afficher le formulaire d'édition du profil
     */
    public function edit()
    {
        $user = Auth::user();
        $langues = Langue::all();
        return view('profile.edit', compact('user', 'langues'));
    }

    /**
     * Mettre à jour le profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $user->id_utilisateur . ',id_utilisateur',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'id_langue' => 'required|exists:langues,id_langue',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mettre à jour les informations de base
        $user->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'date_naissance' => $request->date_naissance,
            'sexe' => $request->sexe,
            'id_langue' => $request->id_langue,
        ]);

        // Gérer l'upload de photo
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $user->update(['photo' => $photoPath]);
        }

        return redirect()->route('profile.edit')
            ->with('success', 'Profil mis à jour avec succès.');
    }
}