FROM php:7.4.13-fpm

# install packages and extension
RUN DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get install -y --no-install-recommends \
		curl wget git zip unzip nano gnupg sed net-tools zlib1g-dev libicu-dev g++ \
		libpng-dev libfreetype6-dev libwebp-dev libjpeg62-turbo-dev iputils-ping libgmp-dev libldap2-dev \
		libssl-dev librabbitmq-dev supervisor \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-configure sockets \
    && docker-php-ext-install sockets \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN dpkg --configure -a && apt-get update && apt-get -y install yarn

RUN yarn -v

RUN apt-get update && apt-get install -y --no-install-recommends apt-utils \
    && HOST_IP="$(ifconfig | sed -En 's/127.0.0.1//;s/.*inet (addr:)?(([0-9]*\.){3}[0-9]*).*/\2/p')" \
    && sed -i "$ a\xdebug.client_host=${HOST_IP}" /usr/local/etc/php/conf.d/xdebug.ini \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install node
RUN curl -sL https://deb.nodesource.com/setup_12.x -o nodesource_setup.sh
RUN bash nodesource_setup.sh
RUN apt-get install nodejs -y
RUN command -v node

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

RUN mkdir -p /var \
    && chmod -R ugo+rwx /var \
    && chmod 777 /run

COPY --chown=www:www . /www

USER www
