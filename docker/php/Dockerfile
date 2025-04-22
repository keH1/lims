FROM php:8.3.13-fpm-alpine3.20 AS builder

RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    g++ \
    make \
    && apk add --no-cache \
    libzip-dev \
    imagemagick-dev \
    mariadb-dev \
    && docker-php-ext-install \
    gd \
    mysqli \
    opcache \
    zip \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

FROM php:8.3.13-fpm-alpine3.20

COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

RUN apk add --no-cache \
    libreoffice \
    openjdk11-jre-headless \
    && adduser -D -u 1000 www-data \
    && rm -rf /var/cache/apk/*

COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/90-php.ini
COPY ./docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/bitrix
EXPOSE 9000
USER www-data