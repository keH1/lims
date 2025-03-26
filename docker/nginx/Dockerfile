FROM nginx:1.20.2

COPY nginx.conf /etc/nginx/
COPY default.conf /etc/nginx/conf.d/
COPY upstream.conf /etc/nginx/conf.d/

RUN usermod -u 1000 www-data

CMD ["nginx"]

EXPOSE 80 443