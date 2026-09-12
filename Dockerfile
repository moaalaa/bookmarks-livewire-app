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

FROM base AS development

RUN apk add --no-cache \
    nodejs  \
    npm

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock package.json package-lock.json ./

RUN composer install \
    --no-interaction \
    --prefer-dist \
    --no-scripts

RUN npm ci

COPY . .

RUN composer dump-autoload \
    --optimize

RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

RUN chmod -R 775 \
    storage \
    bootstrap/cache

EXPOSE 80

CMD ["composer", "dev"]

FROM node:26-trixie AS assets

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY . .

RUN npm run build


FROM base AS production

#Important Alpine detail: the Nginx config directory is typically:
#/etc/nginx/http.d/
#rather than the Debian-style:
#/etc/nginx/conf.d/

RUN apk add --no-cache \
    nginx

COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

COPY docker/entrypoint.sh /usr/local/bin/entrypoint

RUN chmod +x /usr/local/bin/entrypoint

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .

RUN composer dump-autoload \
    --optimize \
    --no-dev

COPY --from=assets /app/public/build ./public/build

RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

RUN chmod -R 775 \
    storage \
    bootstrap/cache

EXPOSE 80

CMD ["/usr/local/bin/entrypoint"]
