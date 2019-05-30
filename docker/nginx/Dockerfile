FROM frapsoft/openssl as ssl

WORKDIR /app

COPY . /app

RUN ls -la

RUN openssl req -x509 -nodes -days 365 \
        -newkey rsa:2048 -keyout /app/docker/nginx/ssl/private/selfsigned.key \
        -out /app/docker/nginx/ssl/certs/selfsigned.crt \
        -subj "/C=br/ST=br/L=br/O=br/OU=docker/CN=localhost.teste"

RUN openssl dhparam -out /app/docker/nginx/ssl/certs/dhparam.pem 2048

FROM composer:1.7 as vendor

WORKDIR /app

COPY . /app
RUN ls -la

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

FROM nginx:mainline-alpine

COPY docker/nginx/ /etc/nginx/conf.d/

COPY . /var/www/html

# support running as arbitrary user which belogs to the root group
RUN chmod g+rwx /var/cache/nginx /var/run /var/log/nginx
RUN chgrp -R root /var/cache/nginx

# comment user directive as master process is run as user in OpenShift anyhow
RUN sed -i.bak 's/^user/#user/' /etc/nginx/nginx.conf
COPY --from=vendor /app/vendor/ /var/www/html/vendor/

COPY --from=ssl /app/docker/nginx/ssl /etc/ssl/

RUN chmod -R 777 /var/www/html/storage

EXPOSE 8081

EXPOSE 8443

RUN addgroup nginx root

USER nginx

ENTRYPOINT ["nginx","-g","daemon off;"]
