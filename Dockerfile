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

# Storage + cache dirs with correct permissions
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["sh", "-c", "echo 'PHP:' && php -v | head -1 && echo 'APP_KEY:' && echo $APP_KEY | cut -c1-20 && echo 'DB_HOST:' && echo $DB_HOST && echo 'PORT:' && echo $PORT && php artisan migrate --force 2>&1 && exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
