#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Créer les dossiers nécessaires s'ils n'existent pas
mkdir -p storage/framework/sessions
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Configurer les permissions
chmod -R 775 storage bootstrap/cache

# Générer la clé d'application si nécessaire
php artisan key:generate --force

# Exécuter les migrations (avec gestion d'erreur pour éviter les échecs)
echo "📦 Running migrations..."
php artisan migrate --force || {
    echo "⚠️  Migration error, but continuing..."
    # Vérifier si les tables essentielles existent
    php artisan migrate:status || true
}

# Vider tous les caches (utilise le driver file, pas database)
echo "🧹 Clearing caches..."
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

# Optimiser l'application (seulement si possible)
echo "⚡ Optimizing application..."
php artisan config:cache 2>/dev/null || php artisan config:clear
php artisan route:cache 2>/dev/null || php artisan route:clear
php artisan view:cache 2>/dev/null || php artisan view:clear

# Démarrer le serveur
echo "🌐 Starting web server on port 10000..."
exec php -S 0.0.0.0:10000 -t public

