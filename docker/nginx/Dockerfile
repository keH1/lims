FROM nginx:1.26.0-alpine

COPY docker/nginx/nginx.conf /etc/nginx/
COPY docker/nginx/default.conf /etc/nginx/conf.d/
COPY docker/nginx/upstream.conf /etc/nginx/conf.d/

RUN adduser -D -u 1000 -G www-data www-data

CMD ["nginx", "-g", "daemon off;"]

EXPOSE 80 443