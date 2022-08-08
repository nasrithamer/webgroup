FROM php:8.1.3-fpm-buster

## Customized Section By Nasri Thamer | thamer.nasri@gmail.com

### Section Tools
# Notice: graphviz is needed by workflow component (symfony) for command dump (svg, png ...)
RUN apt-get update && apt-get upgrade -y && apt-get install -y wget git vim nano graphviz
RUN git config --global user.email "thamer.nasri@gmail.com" \
    &&  git config --global user.name "Nasri Thamer"
# NodeJs version lts 14.x + npm 6.x
RUN curl -sL https://deb.nodesource.com/setup_14.x -o setup_14.sh \
 && sh ./setup_14.sh \
 && apt-get update \
 && apt-get install -y nodejs \
 && npm install -g yarn

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php && php -r "unlink('composer-setup.php');" && mv composer.phar /usr/local/bin/composer
# Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# Section PHP Extensions
# Extensions inspired from book **the fast track https://symfony.com/doc/current/the-fast-track/en/1-tools.html#php **
## intl
RUN apt-get update && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl
## pdo_pgsql
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql
## pdo_mysql
RUN docker-php-ext-configure pdo_mysql \
    && docker-php-ext-install pdo_mysql
## xsl
RUN apt-get update && apt-get install -y libxslt-dev \
    && docker-php-ext-install xsl \
    && docker-php-ext-enable xsl
## amqp | there is no version stable for PHP8 :( | the latest stable version 1.10.2 doesn't works with it
## https://pecl.php.net/package/amqp
RUN apt-get update && apt-get install -y librabbitmq-dev \
    && rm -rf /var/lib/apt/lists/* \
    && pecl install amqp-1.11.0beta \
    && docker-php-ext-enable amqp
## gd
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd
## redis
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis
## zip
RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip
# OPCache
RUN docker-php-ext-install opcache \
    && docker-php-ext-enable opcache

# APCU
RUN pecl install apcu \
    && docker-php-ext-enable apcu


COPY php.ini /usr/local/etc/php/

WORKDIR /var/www/html

EXPOSE 80