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

# Storage + cache dirs AVANT composer install : package:discover post-autoload
# écrit dans bootstrap/cache/packages.php et fail si le dossier n'est pas writable.
# Doit donc précéder `composer install`, pas le suivre.
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# PHP dependencies — --no-scripts évite le catch-22 package:discover :
# le hook lit routes/web.php qui référence BriefWizard::class, dont le binding
# Livewire n'est pas encore registré (c'est justement ce que package:discover
# fait). On régénère packages.php au boot (start.sh) sans charger les routes.
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# JS build
RUN rm -f package-lock.json && npm install && npm run build

COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
