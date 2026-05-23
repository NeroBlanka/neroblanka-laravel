FROM php:8.4-cli-bookworm

# System dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl git unzip libpq-dev libpng-dev libzip-dev libonig-dev libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql gd bcmath zip mbstring xml

# Node.js 22
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# JS build
RUN rm -f package-lock.json && npm install && npm run build

EXPOSE 8080

CMD php artisan migrate --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
