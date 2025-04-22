FROM nginx:1.20.2

COPY ./install /app/install
COPY ./help /app/help
COPY ./assets /app/assets
COPY ./application /app/application

RUN chown -R 1000:33 /app
RUN usermod -u 1000 www-data

EXPOSE 80 443

CMD ["nginx"]