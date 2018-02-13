FROM composer:1.6.3

RUN docker-php-ext-install pdo pdo_mysql

ENV WORKDIR=/usr/src/app

RUN mkdir -p $WORKDIR
WORKDIR $WORKDIR

COPY composer.json $WORKDIR
RUN composer install --ignore-platform-reqs --no-scripts

VOLUME [ "$WORKDIR/vendor" ]
VOLUME [ "/var/lib/mysql" ]

EXPOSE 4211
CMD php -S 0.0.0.0:4211