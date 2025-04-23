FROM alpine:latest

COPY --chown=1000:33 . /app
