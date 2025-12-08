<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Langue;
use App\Models\Parler;
use Illuminate\Support\Facades\Auth;

class RegionController extends Controller
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

        $regions = Region::withCount('contenus')
            ->orderBy('id_region', 'desc')
            ->get();
            
        return view('region.index', compact('regions'));
    }

    /**
     * Show the form for creating a new resource (Admin seulement).
     */
    public function create()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $langues = Langue::all();
        return view('region.create', compact('langues'));
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
            'nom_region' => 'required|string|max:255|unique:regions,nom_region',
            'description' => 'required|string',
            'population' => 'nullable|integer',
            'superficie' => 'nullable|numeric',
            'localisation' => 'nullable|string|max:500',
            'langues' => 'required|array',
            'langues.*' => 'exists:langues,id_langue',
        ]);

        // Créer la région
        $region = Region::create([
            'nom_region' => $data['nom_region'],
            'description' => $data['description'],
            'population' => $data['population'],
            'superficie' => $data['superficie'],
            'localisation' => $data['localisation'],
        ]);

        // Associer les langues parlées dans cette région
        foreach ($data['langues'] as $id_langue) {
            Parler::create([
                'id_region' => $region->id_region,
                'id_langue' => $id_langue,
            ]);
        }

        return redirect()->route('regions.index')
            ->with('success', 'Région créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $region = Region::with(['contenus', 'langues'])->findOrFail($id);
        
        // Tout le monde peut voir une région (même non connecté)
        return view('region.show', compact('region'));
    }

    /**
     * Show the form for editing the specified resource (Admin seulement).
     */
    public function edit(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $region = Region::with('langues')->findOrFail($id);
        $langues = Langue::all();
        
        return view('region.edit', compact('region', 'langues'));
    }

    /**
     * Update the specified resource in storage (Admin seulement).
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        $region = Region::findOrFail($id);

        $data = $request->validate([
            'nom_region' => 'required|string|max:255|unique:regions,nom_region,' . $region->id_region . ',id_region',
            'description' => 'required|string',
            'population' => 'nullable|integer',
            'superficie' => 'nullable|numeric',
            'localisation' => 'nullable|string|max:500',
            'langues' => 'required|array',
            'langues.*' => 'exists:langues,id_langue',
        ]);

        // Mettre à jour la région
        $region->update($data);

        // Mettre à jour les langues parlées
        Parler::where('id_region', $region->id_region)->delete();
        foreach ($data['langues'] as $id_langue) {
            Parler::create([
                'id_region' => $region->id_region,
                'id_langue' => $id_langue,
            ]);
        }

        return redirect()->route('regions.index')
            ->with('success', 'Région modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage (Admin seulement).
     */
    public function destroy(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Action réservée aux administrateurs.');
        }

        $region = Region::findOrFail($id);
        
        // Vérifier s'il y a des contenus associés
        if ($region->contenus()->count() > 0) {
            return redirect()->route('regions.index')
                ->with('error', 'Impossible de supprimer cette région car elle contient des contenus.');
        }

        // Supprimer les associations de langues
        Parler::where('id_region', $region->id_region)->delete();
        
        $region->delete();

        return redirect()->route('regions.index')
            ->with('success', 'Région supprimée avec succès.');
    }

    /**
     * API pour récupérer les régions (pour les selects)
     */
    public function apiRegions()
    {
        $regions = Region::select('id_region', 'nom_region')
            ->orderBy('nom_region')
            ->get();
            
        return response()->json($regions);
    }
}