<?php

namespace App\Http\Controllers;

use App\Models\Contenu;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class PaiementController extends Controller
{
    /**
     * Constructeur : initialise Stripe
     */
    public function __construct()
    {
        // Initialiser Stripe avec votre clé secrète
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Afficher la page d'achat d'un contenu premium
     * URL: /contenus/{id}/acheter (GET)
     */
    public function showAchat($idContenu)
    {
        try {
            $contenu = Contenu::with(['region', 'langue', 'auteur'])->findOrFail($idContenu);
            
            // Vérifier si le contenu est premium
            if (!$contenu->est_premium) {
                return redirect()
                    ->route('contenus.show.public', $contenu->id_contenu)
                    ->with('info', 'Ce contenu est gratuit, aucun paiement nécessaire.');
            }
            
            // Si l'utilisateur n'est pas connecté
            if (!auth()->check()) {
                return redirect()
                    ->route('login')
                    ->with('info', 'Veuillez vous connecter pour acheter ce contenu.');
            }
            
            $utilisateur = auth()->user();
            
            // Vérifier si l'utilisateur a déjà acheté ce contenu
            $dejaAchete = Paiement::where('id_utilisateur', $utilisateur->id_utilisateur)
                ->where('id_contenu', $contenu->id_contenu)
                ->where('statut', 'paye')
                ->exists();
            
            if ($dejaAchete) {
                return redirect()
                    ->route('contenus.show.public', $contenu->id_contenu)
                    ->with('info', 'Vous avez déjà acheté ce contenu.');
            }
            
            // Vérifier si l'utilisateur est l'auteur, un admin ou un modérateur
            if ($utilisateur->isAdmin() || 
                $utilisateur->isModerator() ||
                (isset($contenu->id_auteur) && $utilisateur->id_utilisateur == $contenu->id_auteur)) {
                return redirect()
                    ->route('contenus.show.public', $contenu->id_contenu)
                    ->with('info', 'En tant qu\'auteur/administrateur/modérateur, vous avez accès gratuitement.');
            }
            
            // Afficher la page d'achat
            return view('paiement.achat', [
                'contenu' => $contenu,
                'utilisateur' => $utilisateur
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur affichage achat: ' . $e->getMessage());
            return redirect()
                ->route('accueil')
                ->with('error', 'Contenu non trouvé.');
        }
    }

    /**
     * Traiter le paiement (créer la session Stripe)
     * URL: /contenus/{id}/paiement (POST)
     */
    public function processPaiement(Request $request, $idContenu)
    {
        try {
            $contenu = Contenu::findOrFail($idContenu);
            $utilisateur = auth()->user();
            
            // Validations de base
            if (!$contenu->est_premium) {
                return back()->with('error', 'Ce contenu n\'est pas premium.');
            }
            
            if (!$contenu->prix || $contenu->prix <= 0) {
                return back()->with('error', 'Prix non défini pour ce contenu.');
            }
            
            // Vérifier si déjà acheté
            $dejaAchete = Paiement::where('id_utilisateur', $utilisateur->id_utilisateur)
                ->where('id_contenu', $contenu->id_contenu)
                ->where('statut', 'paye')
                ->exists();
            
            if ($dejaAchete) {
                return redirect()
                    ->route('contenus.show.public', $contenu->id_contenu)
                    ->with('info', 'Vous avez déjà acheté ce contenu.');
            }
            
            // Créer une session de paiement Stripe
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'xof', // FCFA
                        'product_data' => [
                            'name' => 'Contenu Premium: ' . $contenu->titre,
                            'description' => 'Accès complet à "' . $contenu->titre . '"',
                            'metadata' => [
                                'contenu_id' => $contenu->id_contenu,
                                'auteur' => ($contenu->auteur->prenom ?? '') . ' ' . ($contenu->auteur->nom ?? 'Auteur inconnu')
                            ]
                        ],
                        'unit_amount' => intval($contenu->prix * 100), // Stripe utilise les centimes
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('paiement.success', [
                    'contenu' => $contenu->id_contenu,
                    'session_id' => '{CHECKOUT_SESSION_ID}'
                ]),
                'cancel_url' => route('paiement.cancel', $contenu->id_contenu),
                'customer_email' => $utilisateur->email,
                'metadata' => [
                    'id_utilisateur' => $utilisateur->id_utilisateur,
                    'id_contenu' => $contenu->id_contenu,
                    'type' => 'achat_contenu'
                ],
            ]);
            
            // Enregistrer la tentative de paiement dans la base de données
            Paiement::create([
                'id_utilisateur' => $utilisateur->id_utilisateur,
                'id_contenu' => $contenu->id_contenu,
                'montant' => $contenu->prix,
                'devise' => 'XOF',
                'statut' => 'en_attente',
                'methode_paiement' => 'stripe',
                'transaction_id' => $session->id,
                'metadata' => [
                    'session_url' => $session->url,
                    'stripe_session_id' => $session->id,
                    'date_creation' => now()->toDateTimeString()
                ]
            ]);
            
            // Rediriger l'utilisateur vers la page de paiement Stripe
            return redirect($session->url);
            
        } catch (ApiErrorException $e) {
            // Erreur Stripe
            Log::error('Erreur Stripe: ' . $e->getMessage());
            return back()
                ->with('error', 'Erreur lors de la création du paiement: ' . $e->getError()->message);
            
        } catch (\Exception $e) {
            // Autre erreur
            Log::error('Erreur traitement paiement: ' . $e->getMessage());
            return back()
                ->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    /**
     * Page de succès après paiement
     * URL: /paiement/success/{contenu} (GET)
     */
    public function success(Request $request, $idContenu)
    {
        try {
            $sessionId = $request->get('session_id');
            $contenu = Contenu::findOrFail($idContenu);
            
            if (!$sessionId) {
                return redirect()
                    ->route('contenus.show.public', $contenu->id_contenu)
                    ->with('error', 'Session de paiement invalide.');
            }
            
            // Récupérer la session Stripe pour vérifier le statut
            $session = Session::retrieve($sessionId);
            
            // Chercher le paiement correspondant
            $paiement = Paiement::where('transaction_id', $session->id)->first();
            
            if (!$paiement) {
                return redirect()
                    ->route('contenus.show.public', $contenu->id_contenu)
                    ->with('error', 'Paiement non trouvé.');
            }
            
            // Vérifier le statut du paiement chez Stripe
            if ($session->payment_status === 'paid') {
                // Mettre à jour le statut du paiement
                $paiement->update([
                    'statut' => 'paye',
                    'metadata' => array_merge(
                        $paiement->metadata ?? [],
                        [
                            'stripe_payment_status' => $session->payment_status,
                            'stripe_customer' => $session->customer,
                            'date_paiement' => now()->toDateTimeString()
                        ]
                    )
                ]);
                
                // Message de succès
                return redirect()
                    ->route('contenus.show.public', $contenu->id_contenu)
                    ->with('success', '🎉 Paiement réussi ! Vous avez maintenant accès au contenu complet.');
                    
            } else {
                // Paiement non complété
                return redirect()
                    ->route('contenus.show.public', $contenu->id_contenu)
                    ->with('warning', 'Paiement en cours de traitement...');
            }
            
        } catch (ApiErrorException $e) {
            Log::error('Erreur vérification paiement Stripe: ' . $e->getMessage());
            return redirect()
                ->route('contenus.show.public', $idContenu)
                ->with('warning', 'Vérification du paiement en cours...');
                
        } catch (\Exception $e) {
            Log::error('Erreur page succès: ' . $e->getMessage());
            return redirect()
                ->route('contenus.show.public', $idContenu)
                ->with('info', 'Merci pour votre achat !');
        }
    }

    /**
     * Page d'annulation
     * URL: /paiement/cancel/{contenu} (GET)
     */
    public function cancel($idContenu)
    {
        try {
            $contenu = Contenu::findOrFail($idContenu);
            
            return redirect()
                ->route('contenus.show.public', $contenu->id_contenu)
                ->with('info', 'Paiement annulé. Vous pouvez réessayer à tout moment.');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('accueil')
                ->with('info', 'Paiement annulé.');
        }
    }

    /**
     * Page de confirmation manuelle (pour tester sans Stripe)
     * URL: /paiement/test/{contenu} (GET) - À SUPPRIMER EN PRODUCTION
     */
    public function testPaiement($idContenu)
    {
        // UNIQUEMENT POUR LE DÉVELOPPEMENT - À SUPPRIMER EN PRODUCTION
        if (!app()->environment('local')) {
            abort(403);
        }
        
        $contenu = Contenu::findOrFail($idContenu);
        $utilisateur = auth()->user();
        
        // Créer un paiement test
        $paiement = Paiement::create([
            'id_utilisateur' => $utilisateur->id_utilisateur,
            'id_contenu' => $contenu->id_contenu,
            'montant' => $contenu->prix ?? 500,
            'devise' => 'XOF',
            'statut' => 'paye',
            'methode_paiement' => 'test',
            'transaction_id' => 'TEST_' . time(),
            'metadata' => ['mode_test' => true]
        ]);
        
        return redirect()
            ->route('contenus.show.public', $contenu->id_contenu)
            ->with('success', '✅ Paiement TEST réussi ! Contenu débloqué.');
    }

    /**
     * Historique des paiements de l'utilisateur
     * URL: /mes-paiements (GET)
     */
    public function mesPaiements()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $utilisateur = auth()->user();
        $paiements = Paiement::where('id_utilisateur', $utilisateur->id_utilisateur)
            ->with('contenu')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $totalDepenses = Paiement::where('id_utilisateur', $utilisateur->id_utilisateur)
            ->where('statut', 'paye')
            ->sum('montant');
        
        return view('paiement.historique', [
            'paiements' => $paiements,
            'totalDepenses' => $totalDepenses
        ]);
    }

    /**
     * Webhook Stripe (pour les notifications de paiement)
     * URL: /stripe/webhook (POST)
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');
        
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\Exception $e) {
            Log::error('Erreur webhook Stripe: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }
        
        // Gérer les différents types d'événements
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;
                
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;
        }
        
        return response()->json(['received' => true]);
    }
    
    /**
     * Gérer l'événement checkout.session.completed
     */
    private function handleCheckoutSessionCompleted($session)
    {
        try {
            $paiement = Paiement::where('transaction_id', $session->id)->first();
            
            if ($paiement && $session->payment_status === 'paid') {
                $paiement->update([
                    'statut' => 'paye',
                    'metadata' => array_merge(
                        $paiement->metadata ?? [],
                        [
                            'stripe_payment_status' => $session->payment_status,
                            'stripe_customer' => $session->customer,
                            'date_paiement' => now()->toDateTimeString(),
                            'webhook_processed' => true
                        ]
                    )
                ]);
                
                Log::info('Paiement confirmé via webhook', [
                    'paiement_id' => $paiement->id_paiement,
                    'session_id' => $session->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur traitement webhook checkout: ' . $e->getMessage());
        }
    }
    
    /**
     * Gérer l'événement payment_intent.succeeded
     */
    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        // Logique supplémentaire si nécessaire
        Log::info('Payment intent succeeded', ['payment_intent_id' => $paymentIntent->id]);
    }
}
