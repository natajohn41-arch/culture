<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Langue;
use App\Models\Region;
use App\Models\Parler;
use Illuminate\Support\Facades\Auth;

class LangueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Vérifier les permissions - Admin et modérateurs peuvent voir
        if (!Auth::check() || (!Auth::user()->isAdmin() && !Auth::user()->isModerator())) {
            abort(403, 'Accès réservé aux administrateurs et modérateurs.');
        }

        $langues = Langue::withCount(['contenus', 'regions'])
            ->orderBy('id_langue', 'desc')
            ->get();
            
        return view('langues.index', compact('langues'));
    }

    /**
     * Show the form for creating a new resource (Admin seulement).
     */
    public function create()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $regions = Region::all();
        return view('langues.create', compact('regions'));
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
            'nom_langue' => 'required|string|max:255|unique:langues,nom_langue',
            'code_langue' => 'required|string|max:10|unique:langues,code_langue',
            'description' => 'required|string',
            'regions' => 'required|array',
            'regions.*' => 'exists:regions,id_region',
        ]);

        // Créer la langue
        $langue = Langue::create([
            'nom_langue' => $data['nom_langue'],
            'code_langue' => $data['code_langue'],
            'description' => $data['description'],
        ]);

        // Associer les régions où cette langue est parlée
        foreach ($data['regions'] as $id_region) {
            Parler::create([
                'id_langue' => $langue->id_langue,
                'id_region' => $id_region,
            ]);
        }

        return redirect()->route('langues.index')
            ->with('success', 'Langue créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $langue = Langue::with(['contenus', 'regions'])->findOrFail($id);
        
        // Tout le monde peut voir une langue (même non connecté)
        return view('langues.show', compact('langue'));
    }

    /**
     * Show the form for editing the specified resource (Admin seulement).
     */
    public function edit(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $langue = Langue::with('regions')->findOrFail($id);
        $regions = Region::all();
        
        return view('langues.edit', compact('langue', 'regions'));
    }

    /**
     * Update the specified resource in storage (Admin seulement).
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        $langue = Langue::findOrFail($id);

        $data = $request->validate([
            'nom_langue' => 'required|string|max:255|unique:langues,nom_langue,' . $langue->id_langue . ',id_langue',
            'code_langue' => 'required|string|max:10|unique:langues,code_langue,' . $langue->id_langue . ',id_langue',
            'description' => 'required|string',
            'regions' => 'required|array',
            'regions.*' => 'exists:regions,id_region',
        ]);

        // Mettre à jour la langue
        $langue->update($data);

        // Mettre à jour les régions où cette langue est parlée
        Parler::where('id_langue', $langue->id_langue)->delete();
        foreach ($data['regions'] as $id_region) {
            Parler::create([
                'id_langue' => $langue->id_langue,
                'id_region' => $id_region,
            ]);
        }

        return redirect()->route('langues.index')
            ->with('success', 'Langue modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage (Admin seulement).
     */
    public function destroy(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Action réservée aux administrateurs.');
        }

        $langue = Langue::findOrFail($id);
        
        // Vérifier s'il y a des contenus associés
        if ($langue->contenus()->count() > 0) {
            return redirect()->route('langues.index')
                ->with('error', 'Impossible de supprimer cette langue car elle est utilisée dans des contenus.');
        }

        // Vérifier s'il y a des utilisateurs qui utilisent cette langue
        if ($langue->utilisateurs()->count() > 0) {
            return redirect()->route('langues.index')
                ->with('error', 'Impossible de supprimer cette langue car elle est utilisée par des utilisateurs.');
        }

        // Supprimer les associations de régions
        Parler::where('id_langue', $langue->id_langue)->delete();
        
        $langue->delete();

        return redirect()->route('langues.index')
            ->with('success', 'Langue supprimée avec succès.');
    }

    /**
     * API pour récupérer les langues (pour les selects)
     */
    public function apiLangues()
    {
        $langues = Langue::select('id_langue', 'nom_langue', 'code_langue')
            ->orderBy('nom_langue')
            ->get();
            
        return response()->json($langues);
    }
}