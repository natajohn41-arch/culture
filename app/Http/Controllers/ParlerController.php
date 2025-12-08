<?php

namespace App\Http\Controllers;

use App\Models\Parler;
use App\Models\Region;
use App\Models\Langue;
use Illuminate\Http\Request;

class ParlerController extends Controller
{
    public function index()
    {
        $parlers = Parler::with(['region', 'langue'])->get();
        return view('parler.index', compact('parlers'));
    }

    public function create()
    {
        return view('parler.create', [
            'regions' => Region::all(),
            'langues' => Langue::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_region' => 'required|exists:regions,id_region',
            'id_langue' => 'required|exists:langues,id_langue',
        ]);

        // Empêcher les doublons
        if (Parler::where('id_region', $request->id_region)
            ->where('id_langue', $request->id_langue)
            ->exists()) 
        {
            return back()->with('error', 'Cette relation existe déjà.');
        }

        Parler::create($request->only('id_region', 'id_langue'));

        return redirect()->route('parler.index')->with('success', 'Association ajoutée.');
    }

    public function edit(Parler $parler)
    {
        return view('parler.edit', [
            'parler' => $parler,
            'regions' => Region::all(),
            'langues' => Langue::all(),
        ]);
    }

    public function update(Request $request, Parler $parler)
    {
        $request->validate([
            'id_region' => 'required|exists:regions,id_region',
            'id_langue' => 'required|exists:langues,id_langue',
        ]);

        // Vérifier doublon
        if (Parler::where('id_region', $request->id_region)
            ->where('id_langue', $request->id_langue)
            ->where('id', '!=', $parler->id)
            ->exists()) 
        {
            return back()->with('error', 'Cette combinaison existe déjà.');
        }

        $parler->update($request->only('id_region', 'id_langue'));

        return redirect()->route('parler.index')->with('success', 'Association mise à jour.');
    }

    public function destroy(Parler $parler)
    {
        $parler->delete();
        return redirect()->route('parler.index')->with('success', 'Association supprimée.');
    }
}
