#!/bin/bash
php composer.phar global require hirak/prestissimo --prefer-source --no-interaction
php composer.phar install --prefer-source --no-interaction
php artisan migrate --seed

