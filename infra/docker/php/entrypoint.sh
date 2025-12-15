#!/usr/bin/env sh
set -e

WORKDIR=/var/www/html
cd "$WORKDIR" 2>/dev/null || exit 1

# Ensure Laravel cache/logs directories exist
mkdir -p \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  bootstrap/cache

# Relax permissions in dev (volume on host may enforce perms differently)
chmod -R 777 storage bootstrap/cache 2>/dev/null || true

# Clear Laravel caches if artisan is available (ignore failures in early boot)
if [ -f artisan ]; then
  php artisan config:clear 2>/dev/null || true
  php artisan cache:clear 2>/dev/null || true
  php artisan route:clear 2>/dev/null || true
  php artisan view:clear 2>/dev/null || true
fi

# Hand off to the default CMD (php-fpm)
exec "$@"
