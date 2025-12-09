# 📋 Liste des Comptes Utilisateurs

## 🔐 Comptes de Test (Seeders)

Ces comptes sont créés automatiquement par `UsersPerRoleSeeder`. **Tous les mots de passe ont été réinitialisés à : `Enaem123`**

| Email | Nom Complet | Rôle | Statut |
|-------|-------------|------|--------|
| `admin@example.test` | Admin | Admin | actif |
| `moderateur@example.test` | Moderateur | Moderateur | actif |
| `auteur@example.test` | Auteur | Auteur | actif |
| `utilisateur@example.test` | Utilisateur | Utilisateur | actif |

## 👥 Comptes Utilisateurs Réels

| Email | Nom Complet | Rôle | Statut |
|-------|-------------|------|--------|
| `jnata313@gmail.com` | NATA | Utilisateur | actif |
| `mauricecomlan@uac.bj` | COMLAN | Admin | actif |
| `auteur@example.com` | Auteur | Auteur | actif |
| `moderateur@example.com` | Mod | Moderateur | actif |
| `user@example.com` | John | Utilisateur | actif |

## ⚠️ Important

- **Mot de passe actuel pour tous les utilisateurs : `Enaem123`**
- Les mots de passe sont hashés dans la base de données et ne peuvent pas être récupérés
- Si vous ne pouvez pas vous connecter, réinitialisez le mot de passe avec :

```bash
# Réinitialiser un utilisateur spécifique
php artisan users:reset-passwords --email=VOTRE_EMAIL --password=VOTRE_MOT_DE_PASSE

# Réinitialiser tous les utilisateurs
php artisan users:reset-passwords --password=Enaem123 --force
```

## 🔧 Commandes Utiles

```bash
# Lister tous les utilisateurs
php scripts/list_users.php

# Tester la connexion
php scripts/test_login.php

# Réinitialiser les mots de passe
php artisan users:reset-passwords --help
```

## 📝 Notes

- Les comptes avec `@example.test` sont des comptes de test
- Les comptes avec `@example.com` peuvent être des comptes de développement
- Les comptes avec des emails réels (`@gmail.com`, `@uac.bj`) sont des comptes de production

