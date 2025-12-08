<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeMedia;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;

class TypeMediaController extends Controller
{
    /**
     * Display a listing of the resource (Admin seulement).
     */
    public function index()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $typeMedias = TypeMedia::withCount('medias')
            ->orderBy('id_type_media', 'desc')
            ->get();
            
        return view('typemedia.index', compact('typeMedias'));
    }

    /**
     * Show the form for creating a new resource (Admin seulement).
     */
    public function create()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        return view('typemedia.create');
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
            'nom_media' => 'required|string|max:255|unique:type_medias,nom_media',
            'description' => 'nullable|string|max:500',
            'extensions' => 'nullable|string|max:255',
        ]);

        TypeMedia::create($data);

        return redirect()->route('typemedia.index')
            ->with('success', 'Type de média créé avec succès.');
    }

    /**
     * Display the specified resource (Admin seulement).
     */
    public function show(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $typeMedia = TypeMedia::with('medias')->findOrFail($id);
        return view('typemedia.show', compact('typeMedia'));
    }

    /**
     * Show the form for editing the specified resource (Admin seulement).
     */
    public function edit(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $typeMedia = TypeMedia::findOrFail($id);
        return view('typemedia.edit', compact('typeMedia'));
    }

    /**
     * Update the specified resource in storage (Admin seulement).
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        $typeMedia = TypeMedia::findOrFail($id);

        $data = $request->validate([
            'nom_media' => 'required|string|max:255|unique:type_medias,nom_media,' . $typeMedia->id_type_media . ',id_type_media',
            'description' => 'nullable|string|max:500',
            'extensions' => 'nullable|string|max:255',
        ]);

        $typeMedia->update($data);

        return redirect()->route('typemedia.index')
            ->with('success', 'Type de média modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage (Admin seulement).
     */
    public function destroy(string $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Action réservée aux administrateurs.');
        }

        $typeMedia = TypeMedia::findOrFail($id);
        
        // Vérifier s'il y a des médias associés
        if ($typeMedia->medias()->count() > 0) {
            return redirect()->route('typemedia.index')
                ->with('error', 'Impossible de supprimer ce type de média car il est utilisé dans des médias.');
        }

        $typeMedia->delete();

        return redirect()->route('typemedia.index')
            ->with('success', 'Type de média supprimé avec succès.');
    }

    /**
     * API pour récupérer les types de média (pour les selects)
     */
    public function apiTypesMedia()
    {
        $typesMedia = TypeMedia::select('id_type_media', 'nom_media')
            ->orderBy('nom_media')
            ->get();
            
        return response()->json($typesMedia);
    }
}