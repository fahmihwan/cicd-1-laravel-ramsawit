# OPSIONAL BOLEH PAKE INI BOLEH ENGGA

#!/usr/bin/env bash
set -e

cd /var/www/html

mkdir -p storage/logs \
         storage/framework/cache \
         storage/framework/sessions \
         storage/framework/views \
         bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rwx storage bootstrap/cache || true

if [ -f composer.json ] && [ ! -d vendor ]; then
  composer install --no-interaction --prefer-dist
fi

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf