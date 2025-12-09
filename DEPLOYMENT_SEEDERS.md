# 🌱 Guide de Déploiement des Seeders

## 📋 Génération des Contenus sur le Serveur de Production

Pour générer tous les contenus (tous les types pour chaque région) sur le serveur de production, exécutez les commandes suivantes :

### 1. Exécuter les Seeders de Base (si pas déjà fait)

```bash
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=LangueSeeder
php artisan db:seed --class=RegionSeeder
php artisan db:seed --class=TypeContenuSeeder
php artisan db:seed --class=UsersPerRoleSeeder
```

### 2. Générer Tous les Contenus par Région

```bash
php artisan db:seed --class=EnhancedRegionContentSeeder
```

Cette commande va créer **tous les types de contenus pour chaque région** :
- Article
- Histoire / Légende
- Conte / Fable
- Proverbe / Sagesse
- Chanson / Musique
- Danse traditionnelle
- Recette culinaire
- Artisanat
- Cérémonie / Rituel
- Personnage historique
- Lieu culturel
- Poème
- Vidéo
- Galerie photo
- Document

### 3. Vérifier les Contenus Générés

```bash
php scripts/check_content_by_region.php
```

Cette commande affichera un rapport détaillé des contenus par région.

## 🔐 Réinitialisation des Mots de Passe

Si vous devez réinitialiser les mots de passe des utilisateurs :

```bash
php artisan users:reset-passwords --password=Enaem123 --force
```

## 📊 Statistiques Attendues

Après l'exécution des seeders, vous devriez avoir :
- **13 régions** avec des contenus
- **16 types de contenus** différents
- **~208 contenus** au total (16 types × 13 régions)
- Chaque région devrait avoir **au moins 1 contenu de chaque type**

## ⚠️ Notes Importantes

1. Les seeders vérifient si les contenus existent déjà pour éviter les doublons
2. Si un contenu d'un type existe déjà pour une région, il ne sera pas recréé
3. Pour forcer la création, vous pouvez vider la table `contenus` (attention aux clés étrangères)

## 🚀 Commandes Rapides

Pour tout générer d'un coup :

```bash
php artisan migrate:fresh --seed
# OU
php artisan db:seed
```

Mais attention : `migrate:fresh` va **supprimer toutes les données** !

