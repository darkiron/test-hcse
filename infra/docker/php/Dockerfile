FROM php:8.4-fpm-alpine

# System deps
RUN apk add --no-cache \
    git \
    curl \
    bash \
    libzip \
    libpng \
    libjpeg-turbo \
    freetype \
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
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    zip \
    exif \
    intl \
    gd \
    bcmath \
    opcache \
  && apk del .build-deps

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# PHP config
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini

# Create a non-root user matching common UID/GID (1000)
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data || true

# Expose PHP-FPM port
EXPOSE 9000

CMD ["php-fpm"]
