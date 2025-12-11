# Export de données vers seeders

Ce dossier contient les seeders générés à partir de votre base de données locale.

## Comment exporter vos données locales

1. **Exécutez le script d'export** :
   ```bash
   php scripts/export_database_to_seeders.php
   ```

2. **Les seeders seront créés** dans `database/seeders/exports/`

3. **Les seeders seront automatiquement exécutés** lors du déploiement si vous les avez générés

## Forcer l'exécution des seeders en production

Si vous voulez forcer l'exécution des seeders même si des données existent déjà :

1. **Sur Render**, ajoutez la variable d'environnement :
   ```
   FORCE_SEED=true
   ```

2. **Ou modifiez directement** `DatabaseSeeder.php` pour toujours appeler les seeders

## Note importante

Les seeders d'export utilisent `insertOrIgnore()` pour éviter les doublons.
Si vous voulez remplacer les données existantes, modifiez les seeders pour utiliser `insert()` ou `upsert()`.





