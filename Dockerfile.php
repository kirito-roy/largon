# docker/php/Dockerfile
FROM php:8.2-fpm-alpine

# Install PHP extensions and dependencies for Laravel
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    git \
    libzip-dev \
    sqlite-dev \
    libpng-dev \
    jpeg-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    postgresql-dev \
    icu-dev && \
    docker-php-ext-configure gd --with-jpeg --with-freetype && \
    docker-php-ext-install -j$(nproc) gd pdo_mysql pdo_pgsql pdo_sqlite zip intl opcache

# Set a non-root user
RUN addgroup -g 1000 laravel && adduser -u 1000 -G laravel -s /bin/sh -D laravel
USER laravel
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
