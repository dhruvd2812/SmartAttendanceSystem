FROM php:8.3-fpm

RUN apt-get update && apt-get install -y `
    nginx `
    git `
    unzip `
    curl `
    libzip-dev `
    libpng-dev `
    libonig-dev `
    libxml2-dev `
    && docker-php-ext-install `
        pdo_mysql `
        mbstring `
        exif `
        pcntl `
        bcmath `
        gd `
        zip `
    && apt-get clean `
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install `
    --no-dev `
    --optimize-autoloader `
    --no-interaction

RUN chown -R www-data:www-data storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/sites-available/default

COPY docker/start.sh /start.sh

RUN chmod +x /start.sh

EXPOSE 10000

CMD ["/start.sh"]
