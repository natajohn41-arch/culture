#!/bin/bash
set -e

echo "🔨 Building Laravel application for Render..."

# Installer les dépendances
composer install --no-dev --optimize-autoloader

# Configurer les permissions
chmod -R 775 storage bootstrap/cache

# Ne PAS exécuter config:cache ici car la table cache n'existe pas encore
# Render le fera automatiquement, mais on peut l'éviter en utilisant le driver 'file'

echo "✅ Build completed successfully!"





















