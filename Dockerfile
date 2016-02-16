FROM debian
MAINTAINER Daniel Atkinson <hourd.tasa@gmail.com>

RUN apt-get update && export DEBIAN_FRONTEND=noninteractive && apt-get install -q -y --fix-missing \
  aptitude \
  bash \
  nginx \
  php5-fpm \
#  php5-pdo \
  php5-json \
#  php5-openssl \
  php5-mysql \
#  php5-pdo_mysql \
  php5-mcrypt \
  php5-sqlite \
#  php5-pdo_sqlite \
#  php5-ctype \
#  php5-zlib \
#  php5-phar \
#  php5-dom \
  php5-curl \
  php5-gd \
#  php5-xml \
#  php5-xmlreader \
#  php5-iconv \
  php5-ldap \
#  php5-zip \
  php5-ssh2 \
  libssh2-php \
  ssh \
  git \
  nodejs \
  supervisor \
  mysql-client \
  mysql-server

ADD .docker/build/mysql/mariadb_init.sh /mariadb_init.sh
ADD .docker/build/mysql/run.sh /run.sh
RUN chmod 775 /*.sh
ADD .docker/build/mysql/my.cnf /etc/mysql/my.cnf
VOLUME  ["/etc/mysql", "/var/lib/mysql"]
ENV TERM dumb

RUN mkdir -p /etc/nginx/sites-enabled
RUN mkdir -p /var/run/php-fpm
RUN mkdir -p /var/log/supervisor

RUN rm /etc/nginx/nginx.conf
ADD .docker/build/php/nginx.conf /etc/nginx/nginx.conf
ADD .docker/nginx/sites-enabled/default /etc/nginx/sites-enabled/default
ADD .docker/build/php/fpm/pool.d/www.conf /etc/php5/fpm/pool.d/www.conf

VOLUME ["/var/www", "/etc/nginx/sites-enabled"]

ADD .docker/build/php/nginx-supervisor.ini /etc/supervisor.d/nginx-supervisor.ini
ADD .docker/build/php/composer/auth.json /root/.composer/auth.json
ADD .docker/build/php/.docker/php/php.ini /etc/php/php.ini

ADD .docker/build/php/.docker/scripts/permissions.sh /opt/scripts/permissions.sh
RUN chmod +x /opt/scripts/permissions.sh

EXPOSE 80 9000

WORKDIR /var/www
ADD ./ /var/www
RUN cp /var/www/.env.example /var/www/.env

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor.d/nginx-supervisor.ini"]
