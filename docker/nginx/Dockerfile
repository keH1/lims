FROM nginx:1.26.0-alpine

COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/nginx/upstream.conf /etc/nginx/conf.d/upstream.conf

RUN adduser -D -u 1000 www-data \
    && chown -R www-data:www-data /var/cache/nginx /var/log/nginx

EXPOSE 80
USER www-data