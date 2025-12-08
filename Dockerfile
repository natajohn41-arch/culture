FROM php:8.2-cli

# Installer dépendances système + PostgreSQL
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de travail
WORKDIR /var/www

# Copier les fichiers du projet
COPY . .

# Installer dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Permissions Laravel
RUN chmod -R 775 storage bootstrap/cache

# ===== AUTOMATISATION LARAVEL (clé + cache + migrations) =====
RUN php artisan key:generate --force || true
RUN php artisan config:clear || true
RUN php artisan cache:clear || true
RUN php artisan view:clear || true
RUN php artisan migrate --force || true

# Port Render
EXPOSE 10000

# Démarrage serveur PHP
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
