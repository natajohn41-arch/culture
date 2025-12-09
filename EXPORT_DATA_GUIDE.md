# Guide : Exporter vos données locales vers la production

Ce guide vous explique comment exporter toutes vos données de votre base de données locale et les importer sur votre site hébergé.

## 📋 Étape 1 : Exporter vos données locales

Sur votre machine locale, exécutez :

```bash
php scripts/export_database_to_seeders.php
```

Ce script va :
- Lire toutes les données de votre base de données locale
- Créer des seeders dans `database/seeders/exports/`
- Ces seeders contiendront toutes vos données (utilisateurs, contenus, etc.)

## 📤 Étape 2 : Pousser les seeders sur GitHub

```bash
git add database/seeders/exports/
git commit -m "Add exported data seeders"
git push origin main
```

## 🚀 Étape 3 : Déployer sur Render

### Option A : Forcer l'exécution des seeders (recommandé la première fois)

1. Allez sur votre dashboard Render
2. Sélectionnez votre service
3. Allez dans "Environment"
4. Ajoutez une nouvelle variable d'environnement :
   - **Key** : `FORCE_SEED`
   - **Value** : `true`
5. Sauvegardez et redéployez

Les seeders s'exécuteront automatiquement lors du prochain déploiement.

### Option B : Les seeders s'exécutent automatiquement

Si votre base de données est vide, les seeders s'exécuteront automatiquement. La vérification est maintenant améliorée pour détecter si vous avez des contenus réels.

## 🔄 Mettre à jour les données

Si vous modifiez vos données locales et voulez les mettre à jour en production :

1. Ré-exécutez le script d'export : `php scripts/export_database_to_seeders.php`
2. Les seeders seront mis à jour
3. Poussez sur GitHub : `git add database/seeders/exports/ && git commit -m "Update exported data" && git push`
4. Sur Render, activez `FORCE_SEED=true` et redéployez

## ⚠️ Note importante

Les seeders d'export utilisent `insertOrIgnore()` pour éviter les doublons. Si vous voulez **remplacer** les données existantes au lieu de les ignorer, vous devrez modifier les seeders générés pour utiliser `insert()` ou `upsert()`.

## 🛠️ Dépannage

### Les seeders ne s'exécutent pas

1. Vérifiez que `FORCE_SEED=true` est défini dans Render
2. Vérifiez les logs de déploiement sur Render
3. Les seeders sont dans `database/seeders/exports/` et sont automatiquement chargés par `DatabaseSeeder`

### Erreurs lors de l'export

- Vérifiez que votre base de données locale est accessible
- Vérifiez que toutes les tables existent
- Vérifiez les permissions d'écriture dans `database/seeders/exports/`

