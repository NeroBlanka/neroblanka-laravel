web: php artisan serve --host=0.0.0.0 --port=$PORT
worker: php artisan queue:work --queue=ai,emails --tries=3 --timeout=90 --sleep=3 --max-jobs=500 --max-time=3600
scheduler: php artisan schedule:work
