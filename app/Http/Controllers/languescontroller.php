<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Langue;

class languescontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $langues = langue::orderBy('id_langue', 'desc')->get();
        return view('langues.index', compact('langues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('langues.createe');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom_langue' => 'required|string|max:255',
            'code_langue' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        Langue::create($data);

        return redirect()->route('langues.index')->with('success', 'Langue créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_langue)
    {
        
        $langue = Langue::findOrFail($id_langue);
        return view('langues.show', compact('langue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_langue)
    {
        $langue = Langue::findOrFail($id_langue);
        return view('langues.edit', compact('langue'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_langue)
    {
        $langue = Langue::findOrFail($id_langue);

        $data = $request->validate([
            'nom_langue' => 'required|string|max:255',
            'code_langue' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $langue->update($data);

        return redirect()->route('langues.index')->with('success', 'Langue mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_langue)
    {
        $langue = Langue::findOrFail($id_langue);
        $langue->delete();

        return redirect()->route('langues.index')->with('success', 'Langue supprimée.');
    }
}
