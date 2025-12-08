<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource (Admin seulement).
     */
    public function index()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $roles = Role::withCount('utilisateurs')
            ->orderBy('id', 'desc')
            ->get();
            
        return view('role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource (Admin seulement).
     */
    public function create()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        return view('role.create');
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
            'nom_role' => 'required|string|max:255|unique:roles,nom_role',
            'description' => 'nullable|string|max:500',
        ]);

        Role::create($data);

        return redirect()->route('roles.index') // ← 'roles.index' au pluriel
            ->with('success', 'Rôle créé avec succès.');
    }

    /**
     * Display the specified resource (Admin seulement).
     */
    public function show(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $role = Role::with('utilisateurs')->findOrFail($id);
        return view('role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource (Admin seulement).
     */
    public function edit(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $role = Role::findOrFail($id);
        return view('role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage (Admin seulement).
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        $role = Role::findOrFail($id);

        $data = $request->validate([
            'nom_role' => 'required|string|max:255|unique:roles,nom_role,' . $role->id . ',id',
            'description' => 'nullable|string|max:500',
        ]);

        $role->update($data);

        return redirect()->route('roles.index') // ← 'roles.index' au pluriel
            ->with('success', 'Rôle modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage (Admin seulement).
     */
    public function destroy(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Action réservée aux administrateurs.');
        }

        $role = Role::findOrFail($id);
        
        // Vérifier s'il y a des utilisateurs avec ce rôle
        if ($role->utilisateurs()->count() > 0) {
            return redirect()->route('roles.index') // ← 'roles.index' au pluriel
                ->with('error', 'Impossible de supprimer ce rôle car il est attribué à des utilisateurs.');
        }

        // Empêcher la suppression des rôles de base
        $rolesProteges = ['Admin', 'Moderateur', 'Auteur', 'Utilisateur'];
        if (in_array($role->nom_role, $rolesProteges)) {
            return redirect()->route('roles.index') // ← 'roles.index' au pluriel
                ->with('error', 'Impossible de supprimer ce rôle système.');
        }

        $role->delete();

        return redirect()->route('roles.index') // ← 'roles.index' au pluriel
            ->with('success', 'Rôle supprimé avec succès.');
    }
}