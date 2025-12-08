# Configuration Stripe pour le Projet Culture Bénin

## Installation

Stripe est déjà installé dans le projet via Composer. Si ce n'est pas le cas, exécutez :

```bash
composer require stripe/stripe-php
```

## Configuration

### 1. Obtenir les clés API Stripe

1. Créez un compte sur [Stripe](https://stripe.com)
2. Accédez au [Tableau de bord Stripe](https://dashboard.stripe.com)
3. Allez dans **Developers > API keys**
4. Copiez votre **Publishable key** et votre **Secret key**

### 2. Configuration dans le fichier .env

Ajoutez les clés suivantes dans votre fichier `.env` :

```env
STRIPE_KEY=pk_test_...  # Votre clé publique (test)
STRIPE_SECRET=sk_test_...  # Votre clé secrète (test)
STRIPE_WEBHOOK_SECRET=whsec_...  # Secret du webhook (optionnel pour le développement)
```

**Pour la production**, utilisez les clés `pk_live_...` et `sk_live_...`

### 3. Exécuter les migrations

Exécutez les migrations pour créer les tables nécessaires :

```bash
php artisan migrate
```

Cela créera :
- La table `paiements` pour enregistrer les transactions
- Les champs `est_premium` et `prix` dans la table `contenus`

## Utilisation

### Marquer un contenu comme premium

Dans l'interface d'administration ou lors de la création/modification d'un contenu :

1. Cochez la case "Contenu Premium" (`est_premium = true`)
2. Définissez un prix en FCFA (`prix`)

### Flux de paiement

1. **Utilisateur clique sur "Voir plus"** d'un contenu premium
   - Si non connecté → Redirection vers la page d'achat (qui redirige vers login)
   - Si connecté mais n'a pas payé → Redirection vers la page d'achat
   - Si a déjà payé → Accès au contenu complet

2. **Page d'achat** (`/contenus/{id}/acheter`)
   - Affiche les détails du contenu
   - Affiche le prix
   - Bouton "Payer" qui crée une session Stripe Checkout

3. **Paiement Stripe**
   - Redirection vers Stripe Checkout
   - L'utilisateur paie avec sa carte
   - Redirection vers la page de succès

4. **Page de succès**
   - Vérifie le statut du paiement
   - Met à jour le statut dans la base de données
   - Redirige vers le contenu avec accès complet

## Webhooks Stripe (Production)

Pour recevoir les notifications de paiement en temps réel, configurez un webhook :

1. Dans le tableau de bord Stripe, allez dans **Developers > Webhooks**
2. Cliquez sur **Add endpoint**
3. URL : `https://votre-domaine.com/stripe/webhook`
4. Sélectionnez les événements :
   - `checkout.session.completed`
   - `payment_intent.succeeded`
5. Copiez le **Signing secret** et ajoutez-le dans `.env` comme `STRIPE_WEBHOOK_SECRET`

## Routes disponibles

- `GET /contenus/{id}/acheter` - Page d'achat
- `POST /contenus/{id}/paiement` - Créer une session Stripe
- `GET /paiement/success/{id}` - Page de succès
- `GET /paiement/cancel/{id}` - Page d'annulation
- `GET /mes-paiements` - Historique des paiements
- `POST /stripe/webhook` - Webhook Stripe

## Test en développement

Une route de test est disponible en mode local uniquement :

- `GET /paiement/test/{id}` - Simule un paiement réussi (à supprimer en production)

## Notes importantes

- Les prix sont en **FCFA (XOF)**
- Stripe convertit automatiquement les montants en centimes (multiplie par 100)
- Les auteurs et administrateurs ont accès gratuitement aux contenus premium
- Un utilisateur ne peut acheter qu'une seule fois le même contenu

## Support

Pour plus d'informations, consultez la [documentation Stripe](https://stripe.com/docs)
