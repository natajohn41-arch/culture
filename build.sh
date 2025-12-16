#!/bin/bash
set -e

echo "🔨 Building Laravel application..."

# Installer les dépendances
composer install --no-dev --optimize-autoloader

# Configurer les permissions
chmod -R 775 storage bootstrap/cache

echo "✅ Build completed successfully!"





















