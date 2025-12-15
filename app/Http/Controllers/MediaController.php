<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Contenu;
use App\Models\TypeMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Vérifier les permissions - Admin et modérateurs peuvent voir
        if (!Auth::check() || (!Auth::user()->isAdmin() && !Auth::user()->isModerator() )) {
            abort(403, 'Accès réservé aux administrateurs et modérateurs.');
        }

        $medias = Media::with(['contenu', 'typeMedia'])
            ->orderBy('id_media', 'desc')
            ->get();
            
        return view('media.index', compact('medias'));
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

        $contenus = Contenu::all();
        $typesMedia = TypeMedia::all();
        
        return view('media.create', compact('contenus', 'typesMedia'));
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

        $sourceType = $request->input('source_type', 'file');

        if ($sourceType === 'file') {
            $data = $request->validate([
                'chemin' => 'required|file|mimes:jpeg,png,jpg,gif,webp,mp4,avi,mov,wmv,flv,mkv,webm,mp3,wav,ogg,pdf,doc,docx|max:512000',
                'description' => 'required|string|max:500',
                'id_contenu' => 'required|exists:contenus,id_contenu',
                'id_type_media' => 'required|exists:type_medias,id_type_media',
            ]);

            // Upload du fichier
            $file = $request->file('chemin');
            $chemin = $file->store('medias', 'public');
            $mimeType = $file->getMimeType();
            $taille = $file->getSize();
        } else {
            $data = $request->validate([
                'url' => 'required|url',
                'description' => 'required|string|max:500',
                'id_contenu' => 'required|exists:contenus,id_contenu',
                'id_type_media' => 'required|exists:type_medias,id_type_media',
            ]);

            // Télécharger depuis l'URL
            $result = $this->downloadFromUrl($data['url']);
            $chemin = $result['chemin'] ?? null;
            $mimeType = $result['mime_type'] ?? null;
            $taille = $result['taille'] ?? null;
        }

        // Vérifier que l'utilisateur a le droit d'ajouter un média à ce contenu
        $contenu = Contenu::findOrFail($data['id_contenu']);
        if (Auth::id() !== $contenu->id_auteur && !Auth::user()->isAdmin()) {
            abort(403, 'Vous ne pouvez ajouter des médias qu\'à vos propres contenus.');
        }

        Media::create([
            'chemin' => $chemin,
            'description' => $data['description'],
            'id_contenu' => $data['id_contenu'],
            'id_type_media' => $data['id_type_media'],
            'mime_type' => $mimeType,
            'taille' => $taille,
        ]);

        return redirect()->route('media.index')
            ->with('success', 'Média ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $media = Media::with(['contenu', 'typeMedia'])->findOrFail($id);
        
        // Vérifier les permissions (auteur du contenu ou admin/moderateur)
        if (Auth::id() !== $media->contenu->id_auteur && 
            !Auth::user()->isAdmin() && 
            !Auth::user()->isModerator()) {
            abort(403, 'Accès non autorisé.');
        }

        return view('media.show', compact('media'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $media = Media::findOrFail($id);
        
        // Vérifier les permissions (auteur du contenu ou admin)
        if (Auth::id() !== $media->contenu->id_auteur && !Auth::user()->isAdmin()) {
            abort(403, 'Vous ne pouvez modifier que les médias de vos propres contenus.');
        }

        $contenus = Contenu::all();
        $typesMedia = TypeMedia::all();

        return view('media.edit', compact('media', 'contenus', 'typesMedia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $media = Media::findOrFail($id);

        // Vérifier les permissions (auteur du contenu ou admin)
        if (Auth::id() !== $media->contenu->id_auteur && !Auth::user()->isAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        $data = $request->validate([
            'description' => 'required|string|max:500',
            'id_contenu' => 'required|exists:contenus,id_contenu',
            'id_type_media' => 'required|exists:type_medias,id_type_media',
            'chemin' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,avi,mov,wmv,flv,mkv,webm,mp3,wav,ogg,pdf,doc,docx|max:512000',
        ]);

        // Gérer le nouvel upload si fourni
        if ($request->hasFile('chemin')) {
            // Supprimer l'ancien fichier
            Storage::disk('public')->delete($media->chemin);
            
            // Upload du nouveau fichier
            $file = $request->file('chemin');
            $data['chemin'] = $file->store('medias', 'public');
            $data['mime_type'] = $file->getMimeType();
            $data['taille'] = $file->getSize();
        }

        $media->update($data);

        return redirect()->route('media.index')
            ->with('success', 'Média modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $media = Media::findOrFail($id);

        // Vérifier les permissions (auteur du contenu ou admin)
        if (Auth::id() !== $media->contenu->id_auteur && !Auth::user()->isAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        // Supprimer le fichier physique
        Storage::disk('public')->delete($media->chemin);
        
        $media->delete();

        return redirect()->route('media.index')
            ->with('success', 'Média supprimé avec succès.');
    }

    /**
     * Télécharger un média
     */
    public function download($id)
    {
        $media = Media::findOrFail($id);
        
        // Vérifier que le contenu associé est validé ou que l'utilisateur a les droits
        if ($media->contenu->statut !== 'valide' && 
            Auth::id() !== $media->contenu->id_auteur && 
            !Auth::user()->isAdmin() && 
            !Auth::user()->isModerator()) {
            abort(403, 'Accès non autorisé.');
        }

        return Storage::disk('public')->download($media->chemin);
    }

    /**
     * Télécharger un fichier depuis une URL
     */
    private function downloadFromUrl($url)
    {
        try {
            $contents = file_get_contents($url);
            $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
            $extension = $pathInfo['extension'] ?? 'tmp';
            $filename = uniqid() . '.' . $extension;
            $chemin = 'medias/' . $filename;
            
            Storage::disk('public')->put($chemin, $contents);
            
            $fullPath = storage_path('app/public/' . $chemin);
            $mimeType = mime_content_type($fullPath);
            $taille = filesize($fullPath);
            
            return [
                'chemin' => $chemin,
                'mime_type' => $mimeType,
                'taille' => $taille,
            ];
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors du téléchargement depuis l\'URL: ' . $e->getMessage());
        }
    }
}