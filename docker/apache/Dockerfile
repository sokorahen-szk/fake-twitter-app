FROM php:apache-bullseye

RUN apt-get update \
&& apt-get install -y zlib1g-dev libzip-dev unzip vim sudo\
&& docker-php-ext-install zip

RUN apt-get install -y gnupg
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -\
&& apt-get install -y nodejs\
&& npm install npm@8.12.1 --global

RUN a2enmod rewrite

RUN docker-php-ext-install pdo_mysql

COPY --from=composer:2.4.1 /usr/bin/composer /usr/bin/composer

ADD docker/apache/php.ini /usr/local/etc/php/

ADD docker/apache/config/000-default.conf /etc/apache2/sites-available/
RUN a2ensite 000-default

WORKDIR /var/www/html

COPY ./app /var/www/html
