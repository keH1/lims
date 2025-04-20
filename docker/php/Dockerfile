# Этап сборки
FROM phpdockerio/php:8.3-fpm as builder

RUN apt-get update && \
    apt-get -y --no-install-recommends install \
    php8.3-dev \
    php8.3-gd \
    php8.3-imagick \
    php8.3-mysql \
    php8.3-opcache \
    php8.3-zip \
    libreoffice-writer \
    && apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

FROM php:8.3-fpm-alpine

COPY --from=builder /usr/lib/php/20230829/ /usr/lib/php/modules/
COPY --from=builder /etc/php/8.3/ /etc/php/8.3/

RUN apk add --no-cache \
    libreoffice-writer \
    openjdk11-jre-headless \
    && rm -rf /var/cache/apk/*

COPY --chown=1000:33 ./ /var/www/bitrix/ulab
COPY ./docker/php/php.ini /etc/php/8.3/fpm/conf.d/90-php.ini
COPY ./docker/php/www.conf /etc/php/8.3/fpm/pool.d/www.conf

RUN chown -R 1000:33 /var/www/bitrix
WORKDIR "/var/www/bitrix"
EXPOSE 9000