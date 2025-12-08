FROM php:8.2-cli

# Dépendances système + PostgreSQL
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copier le projet
COPY . .

# Installer dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chmod -R 775 storage bootstrap/cache

# Port Render
EXPOSE 10000

# ✅ TOUT SE FAIT AU DÉMARRAGE (avec ENV Render)
CMD php artisan key:generate --force && \
    php artisan migrate --force && \
    php artisan optimize:clear && \
    php -S 0.0.0.0:10000 -t public
