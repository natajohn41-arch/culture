# ✅ Checklist d'Intégration Stripe - Culture Bénin

## ✅ CE QUI A ÉTÉ FAIT

### 1. Migrations de Base de Données
- ✅ Migration créée : `2025_12_09_000000_add_premium_fields_to_contenus_table.php`
  - Ajoute les champs `est_premium` (boolean) et `prix` (decimal) à la table `contenus`
- ✅ Migration créée : `2025_12_08_081013_create_paiements_table.php`
  - Crée la table `paiements` avec tous les champs nécessaires

### 2. Modèles
- ✅ Modèle `Contenu` mis à jour
  - Ajout de `est_premium` et `prix` dans `$fillable`
  - Ajout des casts appropriés
- ✅ Modèle `Paiement` existe déjà avec toutes les relations
- ✅ Modèle `Utilisateur` mis à jour
  - Ajout de la relation `paiements()`

### 3. Contrôleurs
- ✅ `PaiementController` complètement implémenté avec :
  - `showAchat()` - Affiche la page d'achat
  - `processPaiement()` - Crée la session Stripe
  - `success()` - Gère le retour après paiement
  - `cancel()` - Gère l'annulation
  - `testPaiement()` - Route de test (dev uniquement)
  - `mesPaiements()` - Historique des paiements
  - `webhook()` - Gère les webhooks Stripe
- ✅ `ContenuController` mis à jour
  - `showPublic()` modifié pour vérifier les accès premium

### 4. Routes
- ✅ Toutes les routes Stripe configurées dans `web.php`
- ✅ Routes corrigées dans les vues (remplacement de `contenus.show` par `contenus.show.public`)

### 5. Vues
- ✅ `paiement/achat.blade.php` - Page d'achat (existait déjà)
- ✅ `paiement/historique.blade.php` - Historique des paiements (créée)
- ✅ `contenu/public-show.blade.php` - Modifiée pour gérer le contenu premium
- ✅ `accueil.blade.php` - Boutons "Voir plus" modifiés pour rediriger vers l'achat si premium
- ✅ `contenu/public-index.blade.php` - Boutons "Voir plus" modifiés
- ✅ Badges "PREMIUM" ajoutés dans les listes de contenus

### 6. Configuration
- ✅ `config/services.php` - Configuration Stripe ajoutée
- ✅ `app/Http/Middleware/VerifyCsrfToken.php` - Créé avec exclusion du webhook Stripe

### 7. Documentation
- ✅ `STRIPE_SETUP.md` - Guide complet de configuration
- ✅ `CHECKLIST_STRIPE.md` - Ce fichier

---

## 🔧 CE QUE VOUS DEVEZ FAIRE

### Étape 1 : Exécuter les Migrations

```bash
php artisan migrate
```

Cela créera :
- La table `paiements`
- Les champs `est_premium` et `prix` dans la table `contenus`

### Étape 2 : Obtenir les Clés API Stripe

1. Créez un compte sur [Stripe](https://stripe.com) si vous n'en avez pas
2. Connectez-vous au [Tableau de bord Stripe](https://dashboard.stripe.com)
3. Allez dans **Developers > API keys**
4. Copiez :
   - **Publishable key** (commence par `pk_test_...`)
   - **Secret key** (commence par `sk_test_...`)

### Étape 3 : Configurer le Fichier .env

Ajoutez ces lignes dans votre fichier `.env` :

```env
STRIPE_KEY=pk_test_votre_cle_publique_ici
STRIPE_SECRET=sk_test_votre_cle_secrete_ici
STRIPE_WEBHOOK_SECRET=whsec_votre_secret_webhook_ici
```

**Note :** Pour le développement, vous pouvez laisser `STRIPE_WEBHOOK_SECRET` vide.

### Étape 4 : Tester l'Intégration

1. **Créer un contenu premium :**
   - Connectez-vous en tant qu'auteur ou admin
   - Créez ou modifiez un contenu
   - Cochez "Contenu Premium" (`est_premium = true`)
   - Définissez un prix (ex: 5000 pour 5000 FCFA)

2. **Tester le flux de paiement :**
   - Visitez la page d'accueil ou la liste des contenus
   - Cliquez sur "Voir plus" d'un contenu premium
   - Vous devriez être redirigé vers la page d'achat
   - Cliquez sur "Payer" pour être redirigé vers Stripe Checkout

3. **Tester en mode développement :**
   - Utilisez la route `/paiement/test/{id_contenu}` pour simuler un paiement
   - Cette route fonctionne uniquement en mode `local`

### Étape 5 : Configurer les Webhooks (Production uniquement)

Quand vous serez en production :

1. Dans le tableau de bord Stripe, allez dans **Developers > Webhooks**
2. Cliquez sur **Add endpoint**
3. URL : `https://votre-domaine.com/stripe/webhook`
4. Sélectionnez les événements :
   - `checkout.session.completed`
   - `payment_intent.succeeded`
5. Copiez le **Signing secret** et ajoutez-le dans `.env` comme `STRIPE_WEBHOOK_SECRET`

---

## 🧪 Tests à Effectuer

### Test 1 : Contenu Gratuit
- ✅ Un contenu non-premium doit être accessible à tous
- ✅ Le bouton "Voir plus" doit fonctionner normalement

### Test 2 : Contenu Premium (Non connecté)
- ✅ Un utilisateur non connecté doit être redirigé vers la page d'achat
- ✅ La page d'achat doit rediriger vers la page de connexion

### Test 3 : Contenu Premium (Connecté, non payé)
- ✅ L'utilisateur doit voir un aperçu limité du contenu
- ✅ Un bouton "Acheter maintenant" doit être visible
- ✅ Le prix doit être affiché

### Test 4 : Contenu Premium (Connecté, déjà payé)
- ✅ L'utilisateur doit avoir accès au contenu complet
- ✅ Aucun message de paiement ne doit apparaître

### Test 5 : Auteur/Admin
- ✅ Les auteurs de contenus premium ont accès gratuit
- ✅ Les administrateurs ont accès gratuit à tous les contenus premium

### Test 6 : Paiement Stripe
- ✅ Le bouton "Payer" doit créer une session Stripe
- ✅ Redirection vers Stripe Checkout
- ✅ Après paiement, retour vers le site avec accès au contenu

---

## ⚠️ Notes Importantes

1. **Prix en FCFA (XOF)** : Les prix sont en Francs CFA. Stripe convertit automatiquement en centimes (multiplie par 100).

2. **Mode Test vs Production** :
   - En développement : utilisez les clés `pk_test_...` et `sk_test_...`
   - En production : utilisez les clés `pk_live_...` et `sk_live_...`

3. **Cartes de Test Stripe** :
   - Pour tester les paiements, utilisez les cartes de test Stripe :
     - Carte réussie : `4242 4242 4242 4242`
     - Carte refusée : `4000 0000 0000 0002`
   - Date d'expiration : n'importe quelle date future
   - CVC : n'importe quel code à 3 chiffres

4. **Route de Test** : La route `/paiement/test/{id}` fonctionne uniquement en mode `local`. Elle sera automatiquement désactivée en production.

5. **Webhooks** : Les webhooks sont optionnels en développement mais recommandés en production pour une meilleure fiabilité.

---

## 🐛 Résolution de Problèmes

### Erreur : "Route [contenus.show] not defined"
✅ **RÉSOLU** - Toutes les vues utilisent maintenant `contenus.show.public`

### Erreur : "Stripe API key not set"
- Vérifiez que `STRIPE_SECRET` est bien défini dans `.env`
- Exécutez `php artisan config:clear` après modification de `.env`

### Erreur : "Table 'paiements' doesn't exist"
- Exécutez `php artisan migrate`

### Erreur : "Column 'est_premium' doesn't exist"
- Exécutez `php artisan migrate`

---

## 📝 Prochaines Étapes (Optionnel)

1. Ajouter des champs dans le formulaire de création/modification de contenu pour `est_premium` et `prix`
2. Créer une interface d'administration pour gérer les paiements
3. Ajouter des statistiques de ventes
4. Implémenter des remboursements
5. Ajouter des notifications par email après paiement

---

**Date de création :** 2025-12-09
**Statut :** ✅ Intégration complète, prête pour configuration des clés API

