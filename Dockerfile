FROM composer:2.6 AS builder

WORKDIR /app

COPY . .
RUN composer install --no-dev --optimize-autoloader

FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip

RUN a2enmod rewrite

COPY --from=builder /app .
COPY .env.example .env


RUN chown -R www-data:www-data /var/www/html \
&& chmod -R 775 /var/www/html/storage \
&& chmod -R 775 /var/www/html/bootstrap/cache

COPY docker-initdb.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-initdb.sh
ENTRYPOINT ["docker-initdb.sh"]
CMD ["apache2-foreground"]

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN php artisan key:generate
