FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql
WORKDIR /var/www/app

RUN chown -R www-data:www-data /var/www/app

CMD ["php-fpm"]