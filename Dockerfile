FROM node:16-alpine AS web-builder
WORKDIR /build
COPY ./web /build
RUN npm ci && npm run build

FROM composer:2 AS api-builder
WORKDIR /build
COPY ./api /build
COPY ./api/config.sample.inc.php /app/api/config.inc.php
RUN composer install

FROM alpine:3.16
RUN apk add nginx php8-fpm supervisor
COPY ./conf/supervisord.conf /etc/supervisord.conf
COPY ./conf/nginx.conf /etc/nginx/http.d/default.conf
COPY --from=web-builder /build/dist /app/web
COPY --from=api-builder /build /app/api
WORKDIR /app
EXPOSE 8888
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
