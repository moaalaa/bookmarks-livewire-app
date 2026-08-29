FROM php:8.4-fpm-alpine AS base

WORKDIR /var/www

# for production use php.ini production version
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apk add --no-cache \
    bash \
    git \
    curl \
    unzip \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    libxml2-dev \
    mysql-client

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    intl \
    xml \
    zip \
    opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

FROM base AS development

COPY . .

RUN composer install --no-interaction

RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

RUN chmod -R 775 \
    storage \
    bootstrap/cache

EXPOSE 8000

CMD [ "php", "artisan", "serve" ]

FROM base AS production

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction