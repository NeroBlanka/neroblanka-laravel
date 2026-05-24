#!/bin/sh
set -e
php artisan migrate --force
php artisan queue:work --queue=emails,ai,default --sleep=3 --tries=3 --max-time=3600 &
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
