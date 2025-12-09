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

# Créer le fichier .env s'il n'existe pas (nécessaire pour Laravel)
if [ ! -f .env ]; then
    echo "📝 Creating .env file from environment variables..."
    # Créer un fichier .env minimal avec les variables d'environnement disponibles
    {
        echo "APP_NAME=${APP_NAME:-Laravel}"
        echo "APP_ENV=${APP_ENV:-production}"
        echo "APP_KEY="
        echo "APP_DEBUG=${APP_DEBUG:-false}"
        echo "APP_URL=${APP_URL:-http://localhost}"
        echo ""
        echo "LOG_CHANNEL=${LOG_CHANNEL:-stack}"
        echo "LOG_LEVEL=${LOG_LEVEL:-error}"
        echo ""
        echo "DB_CONNECTION=${DB_CONNECTION:-pgsql}"
        echo "DB_HOST=${DB_HOST:-localhost}"
        echo "DB_PORT=${DB_PORT:-5432}"
        echo "DB_DATABASE=${DB_DATABASE:-}"
        echo "DB_USERNAME=${DB_USERNAME:-}"
        echo "DB_PASSWORD=${DB_PASSWORD:-}"
        echo ""
        echo "CACHE_STORE=${CACHE_STORE:-file}"
        echo "SESSION_DRIVER=${SESSION_DRIVER:-file}"
        echo "QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}"
        echo ""
        echo "STRIPE_KEY=${STRIPE_KEY:-}"
        echo "STRIPE_SECRET=${STRIPE_SECRET:-}"
        echo "STRIPE_WEBHOOK_SECRET=${STRIPE_WEBHOOK_SECRET:-}"
    } > .env
fi

# Générer la clé d'application si nécessaire (seulement si APP_KEY est vide)
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force 2>/dev/null || {
        echo "⚠️  Key generation failed, but continuing..."
        true
    }
fi

# Tester la connexion à la base de données avec retry
echo "🔌 Testing database connection..."
DB_CONNECTED=false
for i in {1..5}; do
    # Désactiver temporairement set -e pour ce test
    set +e
    php artisan migrate:status >/dev/null 2>&1
    DB_TEST_EXIT=$?
    set -e
    
    if [ $DB_TEST_EXIT -eq 0 ]; then
        echo "✅ Database connection successful"
        DB_CONNECTED=true
        break
    else
        if [ $i -eq 5 ]; then
            echo "⚠️  Database connection failed after 5 attempts"
            echo "⚠️  Please check your database credentials in environment variables"
            echo "⚠️  Required: DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD"
        else
            echo "⏳ Database not ready, retrying in 2 seconds... (attempt $i/5)"
            sleep 2
        fi
    fi
done

# Exécuter les migrations (avec retry)
if [ "$DB_CONNECTED" = true ]; then
    echo "📦 Running migrations..."
    MIGRATION_SUCCESS=false
    for i in {1..3}; do
        # Désactiver temporairement set -e pour permettre les retries
        set +e
        php artisan migrate --force
        MIGRATION_EXIT_CODE=$?
        set -e
        
        if [ $MIGRATION_EXIT_CODE -eq 0 ]; then
            echo "✅ Migrations completed successfully"
            MIGRATION_SUCCESS=true
            break
        else
            if [ $i -eq 3 ]; then
                echo "❌ Migration failed after 3 attempts (exit code: $MIGRATION_EXIT_CODE)"
                echo "⚠️  Checking migration status..."
                set +e
                php artisan migrate:status || true
                set -e
            else
                echo "⏳ Migration failed, retrying in 3 seconds... (attempt $i/3)"
                sleep 3
            fi
        fi
    done

    if [ "$MIGRATION_SUCCESS" = false ]; then
        echo "⚠️  WARNING: Migrations did not complete successfully!"
        echo "⚠️  The application will start, but database tables may be missing."
    fi
else
    echo "⚠️  Skipping migrations due to database connection failure"
fi

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

