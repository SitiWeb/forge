cd $FORGE_SITE_PATH
git pull origin $FORGE_SITE_BRANCH

$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock

if [ -f artisan ]; then
    $FORGE_PHP artisan migrate --force
fi

$FORGE_PHP artisan down --render=errors.maintenance || true
git pull
$FORGE_COMPOSER install --no-interaction --prefer-dist --optimize-autoloader --no-dev
$FORGE_PHP artisan migrate --force
$FORGE_PHP artisan db:seed --force
$FORGE_PHP artisan cache:clear
$FORGE_PHP artisan route:clear
$FORGE_PHP artisan view:clear
$FORGE_PHP artisan config:clear
$FORGE_PHP artisan event:clear
$FORGE_PHP artisan optimize:clear
$FORGE_PHP artisan auth:clear-resets
npm ci
npm run prod
$FORGE_PHP artisan up
