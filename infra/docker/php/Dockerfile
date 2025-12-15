FROM php:8.4-fpm-alpine

# System deps
RUN apk add --no-cache \
    git \
    curl \
    bash \
    libzip \
    oniguruma \
    icu-libs \
    tzdata \
    unzip \
    shadow \
    supervisor \
  && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    linux-headers

# PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    zip \
    exif \
    intl \
    mbstring \
    bcmath \
    opcache \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && apk del .build-deps

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# PHP config
COPY infra/docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini

# Xdebug config
COPY infra/docker/php/xdebug.ini /usr/local/etc/php/conf.d/20-xdebug.ini

# Create a non-root user matching common UID/GID (1000)
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data || true

# Entrypoint to ensure Laravel cache/logs directories exist with proper perms
COPY infra/docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose PHP-FPM port
EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
