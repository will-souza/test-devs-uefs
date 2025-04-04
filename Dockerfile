# Estágio de construção
FROM composer:2.6 AS builder

WORKDIR /app

COPY . .

RUN mkdir -p /app/docker && \
    composer install \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-progress \
    --optimize-autoloader \
    --ignore-platform-reqs

RUN chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache

# Estágio de produção
FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

# Instala dependências
RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    g++ \
    make \
    && apk add --no-cache \
    nginx \
    supervisor \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libzip-dev \
    oniguruma-dev \
    freetype-dev \
    libpq \
    libpng \
    libjpeg-turbo \
    libzip \
    redis \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    zip \
    mbstring \
    gd \
    opcache \
    && apk del .build-deps \
    && apk del --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    freetype-dev

# Configuração do PHP
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    echo "opcache.enable=1" >> "$PHP_INI_DIR/conf.d/opcache.ini" && \
    echo "opcache.memory_consumption=128" >> "$PHP_INI_DIR/conf.d/opcache.ini" && \
    echo "opcache.interned_strings_buffer=8" >> "$PHP_INI_DIR/conf.d/opcache.ini" && \
    echo "opcache.max_accelerated_files=4000" >> "$PHP_INI_DIR/conf.d/opcache.ini" && \
    echo "opcache.revalidate_freq=60" >> "$PHP_INI_DIR/conf.d/opcache.ini" && \
    echo "opcache.fast_shutdown=1" >> "$PHP_INI_DIR/conf.d/opcache.ini"

# Criar diretórios necessários
RUN mkdir -p /etc/nginx/conf.d && \
    mkdir -p /var/log/nginx && \
    mkdir -p /run/nginx && \
    mkdir -p /var/log/supervisor

# Configuração do supervisor
COPY docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# Configuração do Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/vhost.conf /etc/nginx/conf.d/default.conf

# Copiar aplicação construída
COPY --from=builder /app .

EXPOSE 80

# Garante permissões adequadas
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Crie um diretório para scripts
RUN mkdir -p /usr/local/bin

# Copie o script de inicialização
COPY docker/startup.sh /usr/local/bin/startup.sh
RUN chmod +x /usr/local/bin/startup.sh

# Defina o comando de entrada
CMD ["/usr/local/bin/startup.sh"]
