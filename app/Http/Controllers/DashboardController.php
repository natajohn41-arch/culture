<?php

namespace App\Http\Controllers;

use App\Models\Contenu;
use App\Models\Commentaire;
use App\Models\Media;
use App\Models\Utilisateur;
use App\Models\TypeMedia;
use App\Models\TypeContenu;
use App\Models\Region;
use App\Models\Langue;
use App\Models\Parler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Si l'utilisateur est Admin, on affiche le dashboard complet avec toutes les statistiques
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }
        // Si c'est un modérateur
        elseif ($user->isModerator()) {
            return $this->moderatorDashboard();
        }
        // Si c'est un auteur
        elseif ($user->isAuthor()) {
            return $this->authorDashboard();
        }
        // Si c'est un utilisateur standard
        else {
            return $this->userDashboard();
        }
    }

    /**
     * Dashboard pour l'administrateur (statistiques complètes)
     */
    private function adminDashboard()
    {
        // Totaux simples
        $totalContenus     = Contenu::count();
        $totalCommentaires = Commentaire::count();
        $totalMedias       = Media::count();
        $totalUtilisateurs = Utilisateur::count();
        $totalTypeMedias   = TypeMedia::count();
        $totalTypeContenus = TypeContenu::count();
        $totalRegions      = Region::count();
        $totalLangues      = Langue::count();
        $totalParler       = Parler::count();

        // Derniers éléments
        $lastContenus = Contenu::with(['region','langue','auteur','typeContenu'])
            ->orderByDesc('date_creation')
            ->take(6)
            ->get();

        $lastCommentaires = Commentaire::with('utilisateur','contenu')
            ->orderByDesc('date')
            ->take(8)
            ->get();

        $lastMedias = Media::with('typeMedia','contenu')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        $lastUtilisateurs = Utilisateur::orderByDesc('date_inscription')->take(6)->get();

        // Contenus par statut
        $contenusParStatut = Contenu::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total','statut');

        // Notes moyennes par contenu
        $notes = Commentaire::join('contenus', 'commentaires.id_contenu', '=', 'contenus.id_contenu')
            
            ->select('contenus.titre', DB::raw('ROUND(AVG(commentaires.note), 2) as avg_note'))
            ->groupBy('contenus.titre')
            ->orderByDesc('avg_note')
            ->limit(10)
            ->pluck('avg_note','titre');

        // Contenus par région
        $contenusParRegion = Contenu::join('regions', 'contenus.id_region', '=', 'regions.id_region')
            ->select('regions.nom_region', DB::raw('count(*) as total'))
            ->groupBy('regions.nom_region')
            ->pluck('total','nom_region');

        // Contenus par langue
        $contenusParLangue = Contenu::join('langues', 'contenus.id_langue', '=', 'langues.id_langue')
            ->select('langues.nom_langue', DB::raw('count(*) as total'))
            ->groupBy('langues.nom_langue')
            ->pluck('total','nom_langue');

        // Médias par type
        $mediasParType = Media::join('type_medias', 'media.id_type_media', '=', 'type_medias.id_type_media')
            ->select('type_medias.nom_media', DB::raw('count(*) as total'))
            ->groupBy('type_medias.nom_media')
            ->pluck('total','nom_media');

        // Parler : nombre de relations par région / langue
        $parlerParRegion = Parler::join('regions', 'parler.id_region', '=', 'regions.id_region')
            ->select('regions.nom_region', DB::raw('count(*) as total'))
            ->groupBy('regions.nom_region')
            ->pluck('total','nom_region');

        $parlerParLangue = Parler::join('langues', 'parler.id_langue', '=', 'langues.id_langue')
            ->select('langues.nom_langue', DB::raw('count(*) as total'))
            ->groupBy('langues.nom_langue')
            ->pluck('total','nom_langue');

        // Contenus en attente de validation
        $contenusEnAttente = Contenu::where('statut', 'en_attente')->count();

        return view('dashboard.admin-content', [
            // Totaux
            'totalContenus'      => $totalContenus,
            'totalCommentaires'  => $totalCommentaires,
            'totalMedias'        => $totalMedias,
            'totalUtilisateurs'  => $totalUtilisateurs,
            'totalTypeMedias'    => $totalTypeMedias,
            'totalTypeContenus'  => $totalTypeContenus,
            'totalRegions'       => $totalRegions,
            'totalLangues'       => $totalLangues,
            'totalParler'        => $totalParler,
            'contenusEnAttente'  => $contenusEnAttente,

            // Derniers éléments
            'lastContenus'       => $lastContenus,
            'lastCommentaires'   => $lastCommentaires,
            'lastMedias'         => $lastMedias,
            'lastUtilisateurs'   => $lastUtilisateurs,

            // Statistiques
            'contenusParStatut'  => $contenusParStatut,
            'notes'              => $notes,
            'contenusParRegion'  => $contenusParRegion,
            'contenusParLangue'  => $contenusParLangue,
            'mediasParType'      => $mediasParType,
            'parlerParRegion'    => $parlerParRegion,
            'parlerParLangue'    => $parlerParLangue,
        ]);
    }

    /**
     * Dashboard pour le modérateur
     */
    private function moderatorDashboard()
    {
        $contenusEnAttente = Contenu::where('statut', 'en_attente')->count();
        $contenusValides = Contenu::where('statut', 'valide')->count();
        $totalCommentaires = Commentaire::count();
        
        $derniersCommentaires = Commentaire::with('utilisateur', 'contenu')
            ->latest('date')
            ->take(5)
            ->get();

        $contenusAValider = Contenu::with(['region','langue','auteur','typeContenu'])
            ->where('statut', 'en_attente')
            ->orderByDesc('date_creation')
            ->take(10)
            ->get();

        return view('dashboard.moderator-content', compact(
            'contenusEnAttente',
            'contenusValides',
            'totalCommentaires',
            'derniersCommentaires',
            'contenusAValider'
        ));
    }

    /**
     * Dashboard pour l'auteur
     */
    private function authorDashboard()
    {
        $user = auth()->user();
        
        $mesContenus = Contenu::where('id_auteur', $user->id_utilisateur)->count();
        $contenusValides = Contenu::where('id_auteur', $user->id_utilisateur)
            ->where('statut', 'valide')->count();
        $contenusEnAttente = Contenu::where('id_auteur', $user->id_utilisateur)
            ->where('statut', 'en_attente')->count();
        
        $mesDerniersContenus = Contenu::where('id_auteur', $user->id_utilisateur)
            ->with(['region','langue','typeContenu'])
            ->latest('date_creation')
            ->take(5)
            ->get();

        return view('dashboard.author-content', compact(
            'mesContenus',
            'contenusValides',
            'contenusEnAttente',
            'mesDerniersContenus'
        ));
    }

    /**
     * Dashboard pour l'utilisateur standard
     */
    private function userDashboard()
    {
        $user = auth()->user();
        
        $mesCommentaires = Commentaire::where('id_utilisateur', $user->id_utilisateur)->count();
        $contenusFavoris = 0; // À implémenter si vous avez un système de favoris
        
        $derniersContenus = Contenu::where('statut', 'valide')
            ->with(['region','langue','typeContenu'])
            ->latest('date_creation')
            ->take(5)
            ->get();

        return view('dashboard.user-content', compact(
            'mesCommentaires',
            'contenusFavoris',
            'derniersContenus'
        ));
    }

    /**
     * Fonction de recherche globale
     */
    public function search(Request $request)
    {
        $q = $request->input('q');

        // On cherche dans plusieurs tables
        $contenus = Contenu::where('titre', 'like', "%$q%")
            ->orWhere('texte', 'like', "%$q%")
            ->get();

        $medias = Media::where('description', 'like', "%$q%")->get();
        $utilisateurs = Utilisateur::where('nom', 'like', "%$q%")
            ->orWhere('prenom', 'like', "%$q%")
            ->orWhere('email', 'like', "%$q%")
            ->get();
        $commentaires = Commentaire::where('texte', 'like', "%$q%")->get();

        return view('search.results', compact(
            'q',
            'contenus',
            'medias',
            'utilisateurs',
            'commentaires'
        ));
    }
}