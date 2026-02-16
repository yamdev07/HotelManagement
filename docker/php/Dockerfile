FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD bash -lc "\
  composer install && \
  php artisan key:generate || true && \
  php artisan migrate --force && \
  php artisan db:seed --class=DemoUserSeeder || true && \
  php artisan serve --host=0.0.0.0 --port=8000 \
"
