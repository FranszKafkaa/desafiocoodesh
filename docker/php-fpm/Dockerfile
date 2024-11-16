FROM php:8.3-fpm

ENV COMPOSER_MEMORY_LIMIT='-1'

RUN apt-get update && \
    apt-get install -y --force-yes --no-install-recommends \
        libmemcached-dev \
        libzip-dev \
        libz-dev \
        libzip-dev \
        supervisor \
        libpq-dev \
        sudo \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libssl-dev \
        openssh-server \
        libmagickwand-dev \
        git \
        cron \
        nano \
        libxml2-dev \
        libreadline-dev \
        libgmp-dev \
        mariadb-client \
        unzip

RUN groupadd -g 1000 sail \
    && useradd -u 1000 -g 1000 -m -s /bin/bash sail

RUN usermod -aG sudo sail && \
    echo "sail ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers
    


RUN docker-php-ext-install soap

RUN docker-php-ext-install exif

RUN docker-php-ext-install pcntl

RUN docker-php-ext-install zip

RUN docker-php-ext-install pdo_mysql

RUN docker-php-ext-install pdo_pgsql

RUN docker-php-ext-install bcmath

RUN docker-php-ext-install intl

RUN docker-php-ext-install gmp

RUN pecl install redis && docker-php-ext-enable redis

RUN pecl install imagick && \
    docker-php-ext-enable imagick

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb


RUN docker-php-ext-install gd && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

RUN pecl install xdebug

RUN pecl install memcached && docker-php-ext-enable memcached

RUN curl -s http://getcomposer.org/installer | php && \
    echo "export PATH=${PATH}:/var/www/vendor/bin" >> ~/.bashrc && \
    mv composer.phar /usr/local/bin/composer

RUN . ~/.bashrc

RUN echo "* * * * * cd /var/www/laravel/current && php artisan schedule:run >> /dev/null 2>&1"  >> /etc/cron.d/laravel-scheduler

RUN chmod 0644 /etc/cron.d/laravel-scheduler

RUN chown sail:sail /etc/cron.d/laravel-scheduler

RUN crontab -u sail /etc/cron.d/laravel-scheduler

COPY supervisord.conf /etc/supervisor/supervisord.conf

RUN touch /var/log/cron.log

RUN docker-php-ext-enable xdebug

RUN echo " xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini 
RUN echo "sail = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini 


ADD ./laravel.ini /usr/local/etc/php/conf.d

RUN echo '#!/bin/bash\n/usr/local/bin/php /var/www/vendor/bin/dep "$@"' > /usr/bin/dep

RUN chmod +x /usr/bin/dep

RUN echo '#!/bin/bash\n/usr/local/bin/php /var/www/artisan "$@"' > /usr/bin/art

RUN chmod +x /usr/bin/art

RUN echo '#!/bin/bash\n/usr/local/bin/php /var/www/artisan migrate "$@"' > /usr/bin/migrate

RUN chmod +x /usr/bin/migrate

RUN echo '#!/bin/bash\n/usr/local/bin/php /var/www/artisan migrate:fresh --seed' > /usr/bin/fresh

RUN chmod +x /usr/bin/fresh

RUN echo '#!/bin/bash\n/usr/local/bin/php /var/www/artisan config:clear\n/var/www/vendor/bin/phpunit -d memory_limit=2G --stop-on-error --stop-on-failure --testdox-text=tests/report.txt "$@"' > /usr/bin/t

RUN chmod +x /usr/bin/t

RUN echo '#!/bin/bash\n/usr/local/bin/php /var/www/artisan config:clear\n/bin/bash\n/usr/local/bin/php /var/www/artisan dusk -d memory_limit=2G --stop-on-error --stop-on-failure --testdox-text=tests/report-dusk.txt "$@"' > /usr/bin/d

RUN chmod +x /usr/bin/d

RUN rm -r /var/lib/apt/lists/*
RUN chmod 777 /var/log

WORKDIR /var/www/laravel/current

COPY ./docker-entrypoint.sh /usr/local/bin/

RUN chmod +x /usr/local/bin/docker-entrypoint.sh
RUN ln -s /usr/local/bin/docker-entrypoint.sh /
USER sail
ENTRYPOINT ["docker-entrypoint.sh"]

EXPOSE 9000
CMD ["/usr/bin/supervisord"]