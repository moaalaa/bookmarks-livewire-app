FROM php:8.4-fpm-alpine AS base

WORKDIR /var/www

# for production use php.ini production version
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apk add --no-cache \
    bash \
    git \
    curl \
    unzip \
    nginx \
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

FROM base AS builder
#Important Alpine detail: the Nginx config directory is typically:
#/etc/nginx/http.d/
#rather than the Debian-style:
#/etc/nginx/conf.d/

COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

COPY docker/entrypoint.sh /usr/local/bin/entrypoint

RUN chmod +x /usr/local/bin/entrypoint

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


COPY composer.json composer.lock ./

COPY package.json package-lock.json ./

COPY . .

FROM builder AS development

RUN composer install --no-interaction

RUN npm install

RUN npm run build

RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

RUN chmod -R 775 \
    storage \
    bootstrap/cache

EXPOSE 80

CMD ["/usr/local/bin/entrypoint"]

FROM builder AS production

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction


RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

RUN chmod -R 775 \
    storage \
    bootstrap/cache

EXPOSE 80

CMD ["/usr/local/bin/entrypoint"]
