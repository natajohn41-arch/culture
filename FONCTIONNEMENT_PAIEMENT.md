# 💳 Fonctionnement du Paiement - Culture Bénin

## 📋 Vue d'ensemble

Le système de paiement permet aux utilisateurs d'acheter l'accès à des contenus premium. Le paiement se fait via **Stripe**, une plateforme de paiement sécurisée.

---

## 🔄 Flux de Paiement Complet

### 1️⃣ **L'utilisateur découvre un contenu premium**

Quand un utilisateur visite un contenu marqué comme **premium** (`est_premium = true`), le système vérifie automatiquement :

- ✅ **Si l'utilisateur n'est pas connecté** → Redirection vers la page de connexion
- ✅ **Si l'utilisateur est connecté** → Vérification de l'accès

### 2️⃣ **Vérification de l'accès**

Le système vérifie si l'utilisateur a déjà accès au contenu :

#### ✅ **Accès GRATUIT (sans paiement) pour :**
- **Administrateurs** : Accès à tous les contenus
- **Modérateurs** : Accès à tous les contenus  
- **Auteurs** : Accès gratuit à leurs propres contenus
- **Utilisateurs ayant déjà payé** : Accès permanent au contenu acheté

#### 💰 **Paiement REQUIS pour :**
- **Utilisateurs standard** : Doivent payer pour accéder aux contenus premium

### 3️⃣ **Processus d'achat**

#### **Étape A : Affichage de la page d'achat**
```
URL : /contenus/{id}/acheter
```

L'utilisateur voit :
- 📄 Aperçu du contenu
- 💵 Prix en FCFA
- 🔒 Informations de sécurité Stripe
- 🛒 Bouton "Payer"

#### **Étape B : Clic sur "Payer"**
```
URL : POST /contenus/{id}/paiement
```

Le système :
1. ✅ Vérifie que le contenu est premium
2. ✅ Vérifie que l'utilisateur n'a pas déjà acheté
3. ✅ Crée une **session Stripe Checkout**
4. ✅ Enregistre une tentative de paiement dans la base de données (statut : `en_attente`)
5. ✅ Redirige l'utilisateur vers **Stripe Checkout** (page de paiement sécurisée)

#### **Étape C : Paiement sur Stripe**

L'utilisateur est redirigé vers la page Stripe où il peut :
- 💳 Entrer ses informations de carte bancaire
- ✅ Confirmer le paiement
- ❌ Annuler le paiement

**Cartes de test Stripe (pour développement) :**
- ✅ Carte acceptée : `4242 4242 4242 4242`
- ❌ Carte refusée : `4000 0000 0000 0002`
- Date d'expiration : N'importe quelle date future
- CVC : N'importe quel code à 3 chiffres

#### **Étape D : Après le paiement**

**Si le paiement réussit :**
```
URL : /paiement/success/{contenu}?session_id={CHECKOUT_SESSION_ID}
```

Le système :
1. ✅ Récupère la session Stripe pour vérifier le statut
2. ✅ Met à jour le paiement dans la base (statut : `paye`)
3. ✅ Enregistre les métadonnées du paiement
4. ✅ Redirige vers le contenu avec un message de succès
5. ✅ L'utilisateur a maintenant accès complet au contenu

**Si le paiement est annulé :**
```
URL : /paiement/cancel/{contenu}
```

Le système :
- ℹ️ Affiche un message d'annulation
- 🔄 L'utilisateur peut réessayer à tout moment

---

## 🔐 Sécurité et Vérifications

### Vérifications effectuées à chaque étape :

1. **Avant l'achat :**
   - ✅ Contenu existe et est premium
   - ✅ Utilisateur est connecté
   - ✅ Utilisateur n'a pas déjà acheté
   - ✅ Utilisateur n'est pas auteur/admin/modérateur

2. **Pendant le paiement :**
   - ✅ Session Stripe valide
   - ✅ Montant correct
   - ✅ Transaction enregistrée

3. **Après le paiement :**
   - ✅ Vérification du statut Stripe
   - ✅ Mise à jour de la base de données
   - ✅ Accès accordé au contenu

---

## 📊 Base de Données

### Table `paiements`

Chaque paiement est enregistré avec :
- `id_utilisateur` : Qui a payé
- `id_contenu` : Quel contenu a été acheté
- `montant` : Montant payé en FCFA
- `statut` : `en_attente`, `paye`, `annule`, `echec`
- `transaction_id` : ID de la session Stripe
- `metadata` : Informations supplémentaires (JSON)

### Statuts des paiements :

- **`en_attente`** : Paiement en cours, session créée
- **`paye`** : Paiement réussi, accès accordé
- **`annule`** : Paiement annulé par l'utilisateur
- **`echec`** : Paiement échoué

---

## 🎯 Quand l'utilisateur paie-t-il ?

### Scénario 1 : Premier accès à un contenu premium
1. Utilisateur clique sur "Voir plus" d'un contenu premium
2. Système détecte qu'il n'a pas payé
3. Affiche un aperçu limité + bouton "Acheter maintenant"
4. Utilisateur clique → Page d'achat
5. Utilisateur clique "Payer" → Redirection Stripe
6. Utilisateur paie → Accès complet

### Scénario 2 : Accès ultérieur
1. Utilisateur clique sur "Voir plus" d'un contenu premium qu'il a déjà acheté
2. Système détecte le paiement dans la base
3. ✅ Accès complet immédiat (pas de paiement)

### Scénario 3 : Utilisateur non connecté
1. Utilisateur clique sur un contenu premium
2. Système détecte qu'il n'est pas connecté
3. Redirection vers la page de connexion
4. Après connexion → Retour au contenu → Processus d'achat

---

## 🔔 Webhooks Stripe (Production)

En production, Stripe envoie des notifications automatiques (webhooks) pour :
- Confirmer les paiements réussis
- Notifier les échecs de paiement
- Mettre à jour les statuts automatiquement

**URL du webhook :** `/stripe/webhook`

---

## 📝 Exemple Concret

### Utilisateur "Jean" veut accéder à "La Légende de la Reine Tassi Hangbé" (Premium, 2500 FCFA)

1. **Jean visite la page du contenu**
   - Il voit un aperçu limité (500 premiers caractères)
   - Message : "Contenu Premium - 2500 FCFA"
   - Bouton : "Acheter maintenant"

2. **Jean clique sur "Acheter maintenant"**
   - Redirection vers `/contenus/5/acheter`
   - Page d'achat avec détails du contenu
   - Bouton "Payer 2500 FCFA"

3. **Jean clique sur "Payer"**
   - Redirection vers Stripe Checkout
   - Jean entre sa carte : `4242 4242 4242 4242`
   - Confirme le paiement

4. **Paiement réussi**
   - Redirection vers `/paiement/success/5`
   - Système vérifie le paiement
   - Message : "🎉 Paiement réussi ! Vous avez maintenant accès au contenu complet."
   - Redirection vers le contenu avec accès complet

5. **Prochaines visites**
   - Jean revient sur le même contenu
   - Système détecte qu'il a déjà payé
   - ✅ Accès complet immédiat (pas de nouveau paiement)

---

## 💡 Points Importants

- ✅ **Un paiement = Accès à vie** : Une fois payé, l'utilisateur a accès permanent
- ✅ **Pas de paiement multiple** : Le système vérifie toujours avant de demander le paiement
- ✅ **Sécurité** : Tous les paiements passent par Stripe (certifié PCI-DSS)
- ✅ **Gratuit pour certains rôles** : Admins, modérateurs et auteurs ont accès gratuit
- ✅ **Historique** : Tous les paiements sont enregistrés dans `/mes-paiements`

---

## 🛠️ Configuration Technique

### Clés Stripe (déjà configurées)
- **Clé publique** : `pk_test_51Sc0o7Ps8ttinZnRKNDeqD0KYdJrPsczJXs6iUYq19BunIKjAaJQtv5UucZecpYPs5tSyP4LqxrQcCBE7tus4kLF00Ko8ZLULY`
- **Clé secrète** : `sk_test_51Sc0o7Ps8ttinZnRbsdP8ZTdB82Lhbdvd0nvVls4iDvXkU8nDk9Qr3jk02g6qtFepdueRNX5GhLfaw4MkETT1IoF00mWvFImdi`

### Routes principales
- `GET /contenus/{id}/acheter` - Page d'achat
- `POST /contenus/{id}/paiement` - Création session Stripe
- `GET /paiement/success/{id}` - Page de succès
- `GET /paiement/cancel/{id}` - Page d'annulation
- `GET /mes-paiements` - Historique des paiements

---

**Date de création :** 2025-12-09  
**Statut :** ✅ Système opérationnel et testé

