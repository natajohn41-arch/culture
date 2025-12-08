<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commentaire;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Contenu;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CommentaireController extends Controller
{
    /**
     * Display a listing of the resource (Admin seulement).
     */
    public function index()
    {
        // Vérifier si l'utilisateur est admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $commentaires = Commentaire::with(['utilisateur', 'contenu'])
            ->orderBy('id_commentaire', 'desc')
            ->get();
            
        return view('commentaire.index', compact('commentaires'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Rediriger vers la page des contenus pour commenter
        return redirect()->route('contenus.public')
            ->with('info', 'Veuillez sélectionner un contenu pour ajouter un commentaire.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $data = $request->validate([
            'id_contenu' => 'required|exists:contenus,id_contenu',
            'texte' => 'required|string|max:1000',
            'note' => 'nullable|integer|min:1|max:5',
        ]);

        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour commenter.');
        }

        // Vérifier que le contenu est validé
        $contenu = Contenu::findOrFail($data['id_contenu']);
        if ($contenu->statut !== 'valide') {
            return back()->with('error', 'Impossible de commenter ce contenu (en attente de validation).');
        }

        // Créer le commentaire
        Commentaire::create([
            'id_contenu' => $data['id_contenu'],
            'id_utilisateur' => Auth::id(), // Utiliser l'utilisateur connecté
            'texte' => $data['texte'],
            'note' => $data['note'] ?? null,
            'date' => now(),
        ]);

        // Rediriger vers le contenu avec un message de succès
        return redirect()->route('contenus.show', $data['id_contenu'])
            ->with('success', 'Commentaire ajouté avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $commentaire = Commentaire::with(['utilisateur', 'contenu'])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('commentaires.index')
                ->with('error', 'Commentaire introuvable.');
        }

        // Vérifier les permissions (auteur du commentaire ou admin)
        if (Auth::id() !== $commentaire->id_utilisateur && !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        return view('commentaire.show', compact('commentaire'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $commentaire = Commentaire::findOrFail($id);
        
        // Vérifier les permissions (auteur du commentaire ou admin)
        if (Auth::id() !== $commentaire->id_utilisateur && !Auth::user()->isAdmin()) {
            abort(403, 'Vous ne pouvez modifier que vos propres commentaires.');
        }

        return view('commentaire.edit', compact('commentaire'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $commentaire = Commentaire::findOrFail($id);

        // Vérifier les permissions (auteur du commentaire ou admin)
        if (Auth::id() !== $commentaire->id_utilisateur && !Auth::user()->isAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        $data = $request->validate([
            'texte' => 'required|string|max:1000',
            'note' => 'nullable|integer|min:1|max:5',
        ]);

        $commentaire->update([
            'texte' => $data['texte'],
            'note' => $data['note'] ?? null,
        ]);

        // Rediriger vers le contenu associé
        return redirect()->route('contenus.show', $commentaire->id_contenu)
            ->with('success', 'Commentaire modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $commentaire = Commentaire::findOrFail($id);

        // Vérifier les permissions (auteur du commentaire ou admin/moderateur)
        $user = Auth::user();
        if ($user->id_utilisateur !== $commentaire->id_utilisateur && 
            !$user->isAdmin() && 
            !$user->isModerator()) {
            abort(403, 'Action non autorisée.');
        }

        $id_contenu = $commentaire->id_contenu; // Sauvegarder l'ID avant suppression
        $commentaire->delete();

        // Rediriger vers le contenu ou la liste selon le contexte
        if (request()->has('from_content')) {
            return redirect()->route('contenus.show', $id_contenu)
                ->with('success', 'Commentaire supprimé avec succès.');
        }

        return redirect()->route('commentaires.index')
            ->with('success', 'Commentaire supprimé avec succès.');
    }

    /**
     * Méthode pour les modérateurs - Modération des commentaires
     */
    public function moderate(Request $request, $id)
    {
        if (!Auth::user()->isModerator() && !Auth::user()->isAdmin()) {
            abort(403, 'Action réservée aux modérateurs.');
        }

        $commentaire = Commentaire::findOrFail($id);
        
        $request->validate([
            'action' => 'required|in:approve,reject',
            'reason' => 'nullable|string|max:500',
        ]);

        if ($request->action === 'approve') {
            // Marquer comme approuvé (vous pouvez ajouter un champ 'statut' si nécessaire)
            $commentaire->update(['statut' => 'approuve']);
            $message = 'Commentaire approuvé.';
        } else {
            // Supprimer le commentaire rejeté
            $commentaire->delete();
            $message = 'Commentaire rejeté et supprimé.';
        }

        return back()->with('success', $message);
    }
}