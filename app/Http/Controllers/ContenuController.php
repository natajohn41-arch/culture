<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contenu;
use App\Models\Region;
use App\Models\Langue;
use App\Models\TypeContenu;
use App\Models\Media;
use App\Models\TypeMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContenuController extends Controller
{
    /**
     * Page d'accueil publique
     */
    public function accueil()
    {
        $contenusRecents = Contenu::with(['region', 'langue', 'typeContenu', 'medias'])
            ->where('statut', 'valide')
            ->orderByDesc('date_creation')
            ->take(6)
            ->get();

        // Charger les régions avec le nombre de contenus validés
        $regions = Region::withCount(['contenus' => function($query) {
                $query->where('statut', 'valide');
            }])
            ->orderBy('nom_region')
            ->get();
            
        $langues = Langue::all();
        $totalContenus = Contenu::where('statut', 'valide')->count();
        $totalRegions = Region::count();
        $totalLangues = Langue::count();
        $totalUtilisateurs = \App\Models\Utilisateur::count();

        return view('accueil', compact(
            'contenusRecents', 
            'regions', 
            'langues',
            'totalContenus',
            'totalRegions',
            'totalLangues',
            'totalUtilisateurs'
        ));
    }

    /**
     * Liste publique des contenus
     */
    public function indexPublic(Request $request)
    {
        $query = Contenu::with(['region', 'langue', 'typeContenu', 'medias'])
            ->where('statut', 'valide');

        // Filtres
        if ($request->filled('region')) {
            $query->where('id_region', $request->region);
        }

        if ($request->filled('langue')) {
            $query->where('id_langue', $request->langue);
        }

        if ($request->filled('type')) {
            $query->where('id_type_contenu', $request->type);
        }

        $contenus = $query->orderByDesc('date_creation')->paginate(9);

        $regions = Region::all();
        $langues = Langue::all();
        $typesContenu = TypeContenu::all();

        return view('contenu.public-index', compact('contenus', 'regions', 'langues', 'typesContenu'));
    }

    /**
     * Affichage d'un contenu spécifique
     */
    public function showPublic($id)
    {
        // Vérifier que l'ID est bien un nombre entier positif
        if (!is_numeric($id) || (int)$id <= 0 || $id != (int)$id) {
            abort(404, 'Contenu introuvable.');
        }
        
        // Convertir en entier pour éviter les problèmes de type
        $id = (int)$id;
        
        $contenu = Contenu::with([
            'region', 
            'langue', 
            'typeContenu', 
            'medias',
            'auteur',
            'commentaires.utilisateur'
        ])->where('statut', 'valide')->findOrFail($id);

        // Vérifier si le contenu est premium et si l'utilisateur a payé
        $aAcces = true;
        $doitPayer = false;
        
        if ($contenu->est_premium) {
            // Si l'utilisateur n'est pas connecté, il doit payer
            if (!auth()->check()) {
                $aAcces = false;
                $doitPayer = true;
            } else {
                $utilisateur = auth()->user();
                
                // Vérifier si l'utilisateur est l'auteur, un admin ou un modérateur (accès gratuit)
                if ($utilisateur->isAdmin() || 
                    $utilisateur->isModerator() ||
                    (isset($contenu->id_auteur) && $utilisateur->id_utilisateur == $contenu->id_auteur)) {
                    $aAcces = true;
                } else {
                    // Vérifier si l'utilisateur a déjà acheté ce contenu
                    $paiement = \App\Models\Paiement::where('id_utilisateur', $utilisateur->id_utilisateur)
                        ->where('id_contenu', $contenu->id_contenu)
                        ->where('statut', 'paye')
                        ->exists();
                    
                    if ($paiement) {
                        $aAcces = true;
                    } else {
                        $aAcces = false;
                        $doitPayer = true;
                    }
                }
            }
        }

        // Contenus similaires
        $contenusSimilaires = Contenu::with(['region', 'langue', 'medias'])
            ->where('statut', 'valide')
            ->where('id_region', $contenu->id_region)
            ->where('id_contenu', '!=', $contenu->id_contenu)
            ->orderByDesc('date_creation')
            ->take(3)
            ->get();

        return view('contenu.public-show', compact('contenu', 'contenusSimilaires', 'aAcces', 'doitPayer'));
    }

    /**
     * Display a listing of the resource (Admin seulement).
     */
    public function index()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $contenus = Contenu::with(['region', 'langue', 'typeContenu', 'auteur'])
            ->orderBy('id_contenu', 'desc')
            ->get();
            
        return view('contenu.index', compact('contenus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vérifier les permissions (auteur ou admin)
        if (!Auth::check() || (!Auth::user()->isAuthor() && !Auth::user()->isAdmin())) {
            abort(403, 'Accès réservé aux auteurs et administrateurs.');
        }

        $regions = Region::all();
        $langues = Langue::all();
        $typesContenu = TypeContenu::all();
        $typesMedia = TypeMedia::all();

        return view('contenu.create', compact('regions', 'langues', 'typesContenu', 'typesMedia'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Vérifier les permissions (auteur ou admin)
        if (!Auth::check() || (!Auth::user()->isAuthor() && !Auth::user()->isAdmin())) {
            abort(403, 'Action non autorisée.');
        }

        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'texte' => 'required|string',
            'id_region' => 'required|exists:regions,id_region',
            'id_langue' => 'required|exists:langues,id_langue',
            'id_type_contenu' => 'required|exists:type_contenus,id_type_contenu',
            'parent_id' => 'nullable|exists:contenus,id_contenu',
            'medias.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,avi,mov,wmv,flv,mkv,webm,mp3,wav,ogg,pdf,doc,docx|max:512000',
            'est_premium' => 'nullable|boolean',
            'prix' => 'nullable|numeric|min:1000|required_if:est_premium,1',
        ]);

        // Déterminer le statut selon le rôle
        $statut = Auth::user()->isAdmin() ? 'valide' : 'en_attente';

        // Créer le contenu

        


        $contenu = Contenu::create([
            'titre' => $data['titre'],
            'texte' => $data['texte'],
            'id_region' => $data['id_region'],
            'id_langue' => $data['id_langue'],
            'id_type_contenu' => $data['id_type_contenu'],
            'parent_id' => $data['parent_id'] ?? null,
            'id_auteur' => Auth::id(),
            'statut' => $statut,
            'date_creation' => now(),
            'date_validation' => $statut === 'valide' ? now() : null,
            'id_moderateur' => $statut === 'valide' ? Auth::id() : null,
            'est_premium' => $request->has('est_premium') && $request->est_premium == '1',
            'prix' => ($request->has('est_premium') && $request->est_premium == '1') ? $data['prix'] ?? null : null,
        ]);

        // Gérer les médias
        if ($request->hasFile('medias')) {
            foreach ($request->file('medias') as $mediaFile) {
                $chemin = $mediaFile->store('medias/contenus', 'public');
                
                // Déterminer le type de média
                $typeMedia = $this->determinerTypeMedia($mediaFile->getMimeType());
                
               Media::create([
                    'chemin' => $chemin,
                    'description' => $mediaFile->getClientOriginalName(),
                    'id_contenu' => $contenu->id_contenu,
                    'id_type_media' => $typeMedia,
                    'fichier' => $mediaFile->getClientOriginalName(), // Champ requis
                    
                ]);
            }
        }

        $redirectRoute = Auth::user()->isAdmin() ? 'contenus.index' : 'mes.contenus.index';
        $message = $statut === 'valide' ? 'Contenu créé et publié.' : 'Contenu créé et en attente de validation.';

        return redirect()->route($redirectRoute)->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contenu = Contenu::with(['region', 'langue', 'typeContenu', 'auteur', 'medias'])->findOrFail($id);
        
        // Vérifier les permissions (auteur, admin, ou contenu validé)
        if ($contenu->statut !== 'valide' && 
            Auth::id() !== $contenu->id_auteur && 
            !Auth::user()->isAdmin() && 
            !Auth::user()->isModerator()) {
            abort(403, 'Accès non autorisé.');
        }

        return view('contenu.show', compact('contenu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // MODIFIEZ la méthode edit() - LIGNE 147 à 167
public function edit(string $id)
{
    $contenu = Contenu::findOrFail($id);
    
    // Vérifier les permissions (auteur du contenu ou admin/moderateur)
    if (Auth::id() !== $contenu->id_auteur && 
        !Auth::user()->isAdmin() && 
        !Auth::user()->isModerator()) {
        abort(403, 'Vous ne pouvez modifier que vos propres contenus.');
    }

    $regions = Region::all();
    $langues = Langue::all();
    $typesContenu = TypeContenu::all();
    $typesMedia = TypeMedia::all();

    // Liste des utilisateurs (nécessaire pour sélectionner l'auteur dans la vue)
    $utilisateurs = \App\Models\Utilisateur::all();

    // Liste des contenus pouvant être choisis comme parent (exclure le contenu courant)
    $contenus = Contenu::where('id_contenu', '!=', $id)->orderByDesc('date_creation')->get();

    // AJOUTEZ CETTE LIGNE pour charger les médias du contenu
    $contenu->load('medias');

    return view('contenu.edit', compact('contenu', 'regions', 'langues', 'typesContenu', 'typesMedia', 'utilisateurs', 'contenus'));
}

// MODIFIEZ la méthode update() - LIGNE 183 à 217
// MODIFIEZ la méthode update() dans ContenuController.php
public function update(Request $request, string $id)
{
    $contenu = Contenu::findOrFail($id);

    // Vérifier les permissions
    if (Auth::id() !== $contenu->id_auteur && 
        !Auth::user()->isAdmin() && 
        !Auth::user()->isModerator()) {
        abort(403, 'Action non autorisée.');
    }

    // Validation
    $data = $request->validate([
        'titre' => 'required|string|max:255',
        'texte' => 'required|string',
        'id_region' => 'required|exists:regions,id_region',
        'id_langue' => 'required|exists:langues,id_langue',
        'id_type_contenu' => 'required|exists:type_contenus,id_type_contenu',
        'parent_id' => 'nullable|exists:contenus,id_contenu',
        'statut' => 'required|in:en_attente,valide,rejete',
        'id_auteur' => Auth::user()->isAdmin() ? 'required|exists:utilisateurs,id_utilisateur' : 'nullable',
        'date_creation' => 'required|date',
        'date_validation' => 'nullable|date',
        'id_moderateur' => 'nullable|exists:utilisateurs,id_utilisateur',
        'medias.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,bmp,webp,mp4,avi,mov,wmv,flv,mkv,webm,mp3,wav,ogg,pdf,doc,docx,txt|max:512000',
        'medias_supprimer' => 'nullable|array',
        // CORRECTION CRITIQUE : 'media' sans 's'
        'medias_supprimer.*' => 'integer|exists:media,id_media',
    ]);

    // Pour les non-admins
    if (!Auth::user()->isAdmin()) {
        $data['id_auteur'] = $contenu->id_auteur;
        // Les auteurs ne peuvent pas changer le statut
        $data['statut'] = $contenu->statut;
    }

    // Mettre à jour le contenu
    $contenu->update($data);

    // SUPPRIMER les médias cochés
    if ($request->has('medias_supprimer')) {
        foreach ($request->medias_supprimer as $mediaId) {
            $media = Media::find($mediaId);
            if ($media && $media->id_contenu == $contenu->id_contenu) {
                // Vérifier si le fichier existe avant de supprimer
                if (Storage::disk('public')->exists($media->chemin)) {
                    Storage::disk('public')->delete($media->chemin);
                }
                $media->delete();
            }
        }
    }

    // AJOUTER les nouveaux médias
    if ($request->hasFile('medias')) {
        foreach ($request->file('medias') as $mediaFile) {
            if ($mediaFile->isValid()) {
                try {
                    $chemin = $mediaFile->store('medias/contenus', 'public');
                    
                    $typeMedia = $this->determinerTypeMedia($mediaFile->getMimeType());
                    
                    // Dans la création des nouveaux médias (méthode update())
Media::create([
    'chemin' => $chemin,
    'description' => $mediaFile->getClientOriginalName(),
    'id_contenu' => $contenu->id_contenu,
    'id_type_media' => $typeMedia,
    'fichier' => $mediaFile->getClientOriginalName(), // AJOUTEZ
    
]);
                } catch (\Exception $e) {
                    \Log::error('Erreur upload média: ' . $e->getMessage());
                    continue;
                }
            }
        }
    }

    $redirectRoute = Auth::user()->isAdmin() ? 'contenus.index' : 'mes.contenus.index';
    return redirect()->route($redirectRoute)->with('success', 'Contenu modifié avec succès.');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(string $id)
{
    $contenu = Contenu::findOrFail($id);

    // Vérifier les permissions (auteur du contenu, admin ou modérateur)
    if (Auth::id() !== $contenu->id_auteur && 
        !Auth::user()->isAdmin() && 
        !Auth::user()->isModerator()) {
        abort(403, 'Action non autorisée.');
    }

    // 1. SUPPRIMER LES COMMENTAIRES ASSOCIÉS
    if ($contenu->commentaires && $contenu->commentaires->count() > 0) {
        $contenu->commentaires()->delete(); // Supprime tous les commentaires
    }

    // 2. Supprimer les médias associés
    foreach ($contenu->medias as $media) {
        if (Storage::disk('public')->exists($media->chemin)) {
            Storage::disk('public')->delete($media->chemin);
        }
        $media->delete();
    }

    // 3. Supprimer le contenu
    $contenu->delete();

    $redirectRoute = Auth::user()->isAdmin() ? 'contenus.index' : 'mes.contenus.index';
    return redirect()->route($redirectRoute)->with('success', 'Contenu supprimé avec succès.');
}

    /**
     * Mes contenus (pour les auteurs)
     */
    public function mesContenus()
    {
        if (!Auth::check() || !Auth::user()->isAuthor()) {
            abort(403, 'Accès réservé aux auteurs.');
        }

        $contenus = Contenu::with(['region', 'langue', 'typeContenu'])
            ->where('id_auteur', Auth::id())
            ->orderBy('id_contenu', 'desc')
            ->get();

        return view('contenu.mes-contenus', compact('contenus'));
    }

    /**
     * Contenus à valider (pour modérateurs et admin)
     */
    public function aValider()
    {
        if (!Auth::check() || (!Auth::user()->isModerator() && !Auth::user()->isAdmin())) {
            abort(403, 'Accès réservé aux modérateurs.');
        }

        $contenus = Contenu::with(['region', 'langue', 'typeContenu', 'auteur'])
            ->where('statut', 'en_attente')
            ->orderBy('date_creation')
            ->get();

        return view('contenu.a-valider', compact('contenus'));
    }

    /**
     * Valider un contenu
     */
    public function valider($id)
    {
        if (!Auth::check() || (!Auth::user()->isModerator() && !Auth::user()->isAdmin())) {
            abort(403, 'Action réservée aux modérateurs.');
        }

        $contenu = Contenu::findOrFail($id);
        $contenu->update([
            'statut' => 'valide',
            'date_validation' => now(),
            'id_moderateur' => Auth::id()
        ]);

        return back()->with('success', 'Contenu validé avec succès.');
    }

    /**
     * Rejeter un contenu
     */
    public function rejeter($id)
    {
        if (!Auth::check() || (!Auth::user()->isModerator() && !Auth::user()->isAdmin())) {
            abort(403, 'Action réservée aux modérateurs.');
        }

        $contenu = Contenu::findOrFail($id);
        $contenu->update([
            'statut' => 'rejete',
            'date_validation' => now(),
            'id_moderateur' => Auth::id()
        ]);

        return back()->with('success', 'Contenu rejeté.');
    }

    /**
     * Déterminer le type de média selon le MIME type
     */
    private function determinerTypeMedia($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 1; // Image
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 2; // Vidéo
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 3; // Audio
        } else {
            return 4; // Document
        }
    }
}