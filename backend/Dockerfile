# PHP 8.3 CLI – Laravelhez ideális
FROM php:8.3-cli

# szükséges csomagok
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip && \
    docker-php-ext-install pdo_mysql zip && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# composer telepítése
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# projekt könyvtár
WORKDIR /var/www/html

# teljes projekt bemásolása
COPY . /var/www/html

# Laravel dependency telepítés
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Laravel permissions fix
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
