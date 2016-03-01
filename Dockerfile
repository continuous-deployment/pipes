FROM hourd/php:latest
ADD ./ /var/www
ADD .env.docker /var/www/.env
RUN php composer.phar global require hirak/prestissimo --prefer-source --no-interaction
RUN php composer.phar install --prefer-source --no-interaction
