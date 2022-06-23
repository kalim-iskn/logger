FROM php:8.1.5-fpm-alpine3.14

USER root

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/bin --filename=composer && \
    composer -V

RUN mkdir -p /opt/app/vendor \
    && mkdir -p /opt/app/storage/logs

USER www-data

WORKDIR /opt/app
