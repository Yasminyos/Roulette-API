FROM php:7.3-fpm

WORKDIR '/var/www/html'

COPY .env ./web/.env
COPY ./etc/src/scheduler.sh /var/www/scheduler.sh
ADD ./etc/src/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN apt-get update
RUN apt-get install -y \
curl \
wget \
git \
supervisor \
libzip-dev \
zip \
&& docker-php-ext-configure zip --with-libzip \
&& docker-php-ext-install zip \
&& docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo "alias phinx='php src/vendor/bin/phinx'" >> ~/.bashrc

ENTRYPOINT ["/usr/bin/supervisord"]
