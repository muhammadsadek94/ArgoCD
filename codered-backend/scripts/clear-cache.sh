cd /var/www

composer dumpautoload

# remove all caches
php artisan clear-compiled
php artisan optimize:clear
php artisan view:clear
php artisan route:clear
php artisan event:clear
php artisan config:clear
php artisan cache:clear
