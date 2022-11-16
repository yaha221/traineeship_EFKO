FROM php:8.1-fpm-alpine3.16

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./conf.d /usr/local/etc/php/conf.d

USER 1000

WORKDIR /app