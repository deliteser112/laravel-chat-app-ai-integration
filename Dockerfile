FROM php:8.1-fpm-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl unzip zip libpq-dev libzip-dev ca-certificates \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install -j$(nproc) pdo_pgsql zip pcntl sockets

RUN pecl install redis && docker-php-ext-enable redis

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
  && apt-get update && apt-get install -y --no-install-recommends nodejs \
  && node -v && npm -v

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --no-interaction || true

COPY . .

RUN mkdir -p /var/www/html/node_modules

RUN useradd -u 1000 -ms /bin/bash appuser \
  && chown -R appuser:appuser /var/www/html
USER appuser

RUN php artisan config:clear || true

EXPOSE 9000
EXPOSE 5173
