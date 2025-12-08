# Guide de déploiement sur Render

## Configuration requise

### Variables d'environnement à configurer dans Render

1. **Base de données PostgreSQL** :
   - `DB_CONNECTION=pgsql`
   - `DB_HOST` (fourni par Render)
   - `DB_PORT=5432`
   - `DB_DATABASE` (nom de la base de données)
   - `DB_USERNAME` (utilisateur de la base)
   - `DB_PASSWORD` (mot de passe de la base)

2. **Application Laravel** :
   - `APP_NAME=Culture`
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL` (URL de votre application Render)

3. **Cache** :
   - `CACHE_STORE=file` (important : utilise le système de fichiers au lieu de la base de données)

4. **Session** :
   - `SESSION_DRIVER=file` (important : utilise le système de fichiers au lieu de la base de données)

5. **Stripe** (si vous utilisez les paiements) :
   - `STRIPE_KEY` (clé publique Stripe)
   - `STRIPE_SECRET` (clé secrète Stripe)

## Étapes de déploiement

1. **Créer une base de données PostgreSQL** sur Render
2. **Créer un service Web** avec :
   - Type : `Web Service`
   - Environment : `Docker`
   - Dockerfile Path : `./Dockerfile`
   - Docker Context : `.`
   - Build Command : (laisser vide, géré par Dockerfile)
   - Start Command : (laisser vide, géré par Dockerfile)

3. **Configurer les variables d'environnement** listées ci-dessus

4. **Déployer** : Render détectera automatiquement le Dockerfile et déploiera l'application

## Résolution des problèmes

### Erreur : "relation cache does not exist"

Cette erreur se produit si le driver de cache est configuré sur `database` mais que la table `cache` n'existe pas encore.

**Solution** : Assurez-vous que `CACHE_STORE=file` est défini dans les variables d'environnement Render.

### Erreur : "relation sessions does not exist"

Cette erreur se produit si le driver de session est configuré sur `database` mais que la table `sessions` n'existe pas encore.

**Solution** : Assurez-vous que `SESSION_DRIVER=file` est défini dans les variables d'environnement Render.

### Les migrations ne s'exécutent pas

Vérifiez que les variables d'environnement de base de données sont correctement configurées dans Render.

### Erreur de permissions

Le script `start.sh` configure automatiquement les permissions. Si vous avez des problèmes, vérifiez que le dossier `storage` et `bootstrap/cache` ont les bonnes permissions.

## Notes importantes

- Le driver de cache par défaut est maintenant `file` au lieu de `database` pour éviter les problèmes de table manquante
- Le driver de session par défaut est maintenant `file` au lieu de `database` pour éviter les problèmes de table manquante
- Les migrations s'exécutent automatiquement au démarrage
- Le serveur PHP intégré écoute sur le port 10000 (configuré pour Render)

