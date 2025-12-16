# 🔐 Identifiants de Connexion

## 📋 Utilisateurs de Test

### 👑 Administrateur
- **Email:** `admin@example.test`
- **Mot de passe:** `password`
- **Rôle:** Admin
- **Accès:** Tous les droits (gestion complète du site)

### 🛡️ Modérateur
- **Email:** `moderateur@example.test`
- **Mot de passe:** `password`
- **Rôle:** Moderateur
- **Accès:** Validation/rejet de contenus, modération des commentaires

### ✍️ Auteur
- **Email:** `auteur@example.test`
- **Mot de passe:** `password`
- **Rôle:** Auteur
- **Accès:** Création et gestion de ses propres contenus

### 👤 Utilisateur Standard
- **Email:** `utilisateur@example.test`
- **Mot de passe:** `password`
- **Rôle:** Utilisateur
- **Accès:** Consultation des contenus, commentaires

## 🔧 Réinitialisation des Mots de Passe

Si vous avez besoin de réinitialiser les mots de passe, exécutez :

```bash
php scripts/fix_user_passwords.php
```

Ce script met à jour les mots de passe de tous les utilisateurs de test avec le mot de passe `password`.

## 🚀 Utilisateur de Production

Pour créer un utilisateur admin en production, configurez les variables d'environnement :

```env
ADMIN_EMAIL=admin@culture.bj
ADMIN_PASSWORD=VotreMotDePasseSecurise
```

Puis exécutez :

```bash
php artisan db:seed --class=Database\Seeders\ProductionUsersSeeder
```

## ⚠️ Notes Importantes

1. **Sécurité:** Changez les mots de passe par défaut en production !
2. **Test:** Les utilisateurs de test sont créés automatiquement par `UsersPerRoleSeeder`
3. **Rôles:** Les rôles sont vérifiés via les méthodes `isAdmin()`, `isModerator()`, `isAuthor()`

## 🔍 Vérification

Pour tester les utilisateurs et leurs rôles :

```bash
php scripts/test_user_login_roles.php
```

## 📝 Problèmes Résolus

✅ Les mots de passe ont été réinitialisés pour tous les utilisateurs de test
✅ La relation `role` est maintenant chargée automatiquement lors de l'authentification
✅ Les méthodes `isAdmin()`, `isModerator()`, `isAuthor()` chargent automatiquement la relation si nécessaire

















