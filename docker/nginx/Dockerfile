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
RUN ls -la
COPY docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

COPY . /var/www/html

# support running as arbitrary user which belogs to the root group
RUN chmod g+rwx /var/cache/nginx /var/run /var/log/nginx
RUN chgrp -R root /var/cache/nginx

# comment user directive as master process is run as user in OpenShift anyhow
RUN sed -i.bak 's/^user/#user/' /etc/nginx/nginx.conf
COPY --from=vendor /app/vendor/ /var/www/html/vendor/

RUN chmod -R 777 /var/www/html/storage

EXPOSE 8081

RUN addgroup nginx root

USER nginx

ENTRYPOINT ["nginx","-g","daemon off;"]
