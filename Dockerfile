FROM nginx:1.20.2

COPY docker/nginx/nginx.conf /etc/nginx/
COPY docker/nginx/default.conf /etc/nginx/conf.d/
COPY docker/nginx/upstream.conf /etc/nginx/conf.d/
COPY --chown=1000:33 ./ /tmp/ulab

RUN usermod -u 1000 www-data

EXPOSE 80 443

CMD ["nginx"]