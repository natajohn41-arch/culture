<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Models\Contenu;

class AdminImportController extends Controller
{
    /**
     * Importe tous les contenus locaux
     * Route sécurisée accessible uniquement aux admins
     */
    public function importAllContents(Request $request)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        // Vérifier le token de sécurité (optionnel mais recommandé)
        $token = $request->input('token') ?? $request->json('token');
        $expectedToken = env('IMPORT_TOKEN', 'default-token');
        
        if ($token && $token !== $expectedToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalide.'
            ], 403);
        }

        try {
            // Compter les contenus avant
            $contenusAvant = Contenu::where('statut', 'valide')->count();

            // Exécuter la commande d'import
            $exitCode = Artisan::call('contents:import-all');
            
            // Compter les contenus après
            $contenusApres = Contenu::where('statut', 'valide')->count();
            $contenusAjoutes = $contenusApres - $contenusAvant;

            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'Import terminé avec succès !',
                'data' => [
                    'contenus_avant' => $contenusAvant,
                    'contenus_apres' => $contenusApres,
                    'contenus_ajoutes' => $contenusAjoutes,
                    'output' => $output
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'import: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche une page simple pour déclencher l'import
     */
    public function showImportPage()
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        $contenusActuels = Contenu::where('statut', 'valide')->count();
        
        return view('admin.import-contents', [
            'contenusActuels' => $contenusActuels
        ]);
    }
}

