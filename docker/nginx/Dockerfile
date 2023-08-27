FROM nginx:1.21

RUN ln -sf /dev/stdout /var/log/nginx/access.log && ln -sf /dev/stderr /var/log/nginx/error.log

ADD div-request-backend.conf /etc/nginx/conf.d/default.conf

VOLUME /var/log/nginx
