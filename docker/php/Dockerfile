FROM php:8.3.13-fpm-alpine3.20 AS builder

RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    g++ \
    make \
    && apk add --no-cache \
    libzip-dev \
    imagemagick-dev \
    mariadb-dev \
    libpng-dev \
    freetype-dev \
    icu-dev \
    libxml2-dev \
    libmcrypt-dev \
    memcached-dev \
    # Зависимости для gd с поддержкой freetype и png
    && docker-php-ext-configure gd --with-freetype --with-png \
    # Установка расширений PHP
    && docker-php-ext-install \
    gd \
    mysqli \
    opcache \
    zip \
    intl \
    soap \
    mbstring \
    # Установка расширений через pecl
    && pecl install imagick mcrypt memcache memcached \
    && docker-php-ext-enable imagick mcrypt memcache memcached \
    # Удаление временных зависимостей
    && apk del .build-deps \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

FROM php:8.3.13-fpm-alpine3.20

COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

RUN apk add --no-cache \
    libreoffice \
    libreoffice-writer \
    libreoffice-calc \
    libreoffice-impress \
    libreoffice-draw \
    libreoffice-math \
    libreoffice-base \
    libreoffice-lang-ru \
    libreoffice-help-ru \
    openjdk11-jre-headless \
    libpng \
    freetype \
    imagemagick \
    icu-libs \
    libxml2 \
    libmcrypt \
    libmemcached \
    # Создание пользователя www-data с UID 1000
    && adduser -D -u 1000 www-data \
    && rm -rf /var/cache/apk/*

COPY ./php.ini /usr/local/etc/php/conf.d/90-php.ini
COPY ./www.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/bitrix
EXPOSE 9000
USER www-data