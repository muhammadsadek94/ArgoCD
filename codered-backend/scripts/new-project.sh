echo -n "Press confirm that you need to start new project / assuming this script in following path project/root/scripts [Y/n]: "
read isUserConfirmedToUsedScript

if [[ "$isUserConfirmedToUsedScript" != 'Y' ]]; then
    echo "Good bye :*"
    exit
fi

databaseIsConfigured="n"

# start your script

cd /var/www # goto project path
composer install

if [ -f ".env" ]; then
    echo ".env is exists"

    echo -n "did you configure database connection? [Y/n]: "
    read databaseIsConfigured
else
    echo ".env is  not exists"
    echo "Create .env file...."
    cp .env.example .env     # build .env file
    php artisan key:generate # generate project key
    databaseIsConfigured="Y"
fi

# remove all caches
php artisan clear-compiled
php artisan optimize:clear
php artisan view:clear
php artisan route:clear
php artisan event:clear
php artisan config:clear
php artisan cache:clear

if [[ "$databaseIsConfigured" == 'Y' ]]; then
    php artisan migrate
fi

# delete authentications drivers
if [[ "$databaseIsConfigured" == 'Y' ]]; then
    echo "install passport..."
    php artisan passport:purge
    php artisan passport:install --force
    echo "passport has been installed..."

    echo "install telescope..."
    php artisan telescope:install
    php artisan telescope:publish
    echo "telescope has been installed..."

    echo "install horizon..."
    php artisan horizon:install
    php artisan horizon:publish
    echo "horizon has been installed..."

    echo "install user-activity..."
    php artisan user-activity:install
    echo "user-activity has been installed..."



fi




exit
