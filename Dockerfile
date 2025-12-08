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

# Dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chmod -R 775 storage bootstrap/cache

# Port Render
EXPOSE 10000

# ✅ Lancement simple et stable
CMD php artisan optimize:clear && \
    php -S 0.0.0.0:10000 -t public
