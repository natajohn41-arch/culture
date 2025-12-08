<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeContenu;
use App\Models\Contenu;
use Illuminate\Support\Facades\Auth;

class TypeContenuController extends Controller
{
    /**
     * Display a listing of the resource (Admin seulement).
     */
    public function index()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $typeContenus = TypeContenu::withCount('contenus')
            ->orderBy('id_type_contenu', 'desc')
            ->get();
            
        return view('typecontenu.index', compact('typeContenus'));
    }

    /**
     * Show the form for creating a new resource (Admin seulement).
     */
    public function create()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        return view('typecontenu.create');
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
            'nom_contenu' => 'required|string|max:255|unique:type_contenus,nom_contenu',
            'description' => 'nullable|string|max:500',
        ]);

        TypeContenu::create($data);

        return redirect()->route('typecontenu.index')
            ->with('success', 'Type de contenu créé avec succès.');
    }

    /**
     * Display the specified resource (Admin seulement).
     */
    public function show(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $typeContenu = TypeContenu::with('contenus')->findOrFail($id);
        return view('typecontenu.show', compact('typeContenu'));
    }

    /**
     * Show the form for editing the specified resource (Admin seulement).
     */
    public function edit(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $typeContenu = TypeContenu::findOrFail($id);
        return view('typecontenu.edit', compact('typeContenu'));
    }

    /**
     * Update the specified resource in storage (Admin seulement).
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        $typeContenu = TypeContenu::findOrFail($id);

        $data = $request->validate([
            'nom_contenu' => 'required|string|max:255|unique:type_contenus,nom_contenu,' . $typeContenu->id_type_contenu . ',id_type_contenu',
            'description' => 'nullable|string|max:500',
        ]);

        $typeContenu->update($data);

        return redirect()->route('typecontenu.index')
            ->with('success', 'Type de contenu modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage (Admin seulement).
     */
    public function destroy(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Action réservée aux administrateurs.');
        }

        $typeContenu = TypeContenu::findOrFail($id);
        
        // Vérifier s'il y a des contenus associés
        if ($typeContenu->contenus()->count() > 0) {
            return redirect()->route('typecontenu.index')
                ->with('error', 'Impossible de supprimer ce type de contenu car il est utilisé dans des contenus.');
        }

        $typeContenu->delete();

        return redirect()->route('typecontenu.index')
            ->with('success', 'Type de contenu supprimé avec succès.');
    }

    /**
     * API pour récupérer les types de contenu (pour les selects)
     */
    public function apiTypesContenu()
    {
        $typesContenu = TypeContenu::select('id_type_contenu', 'nom_contenu')
            ->orderBy('nom_contenu')
            ->get();
            
        return response()->json($typesContenu);
    }
}