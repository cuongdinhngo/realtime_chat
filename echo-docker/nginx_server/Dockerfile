FROM ubuntu:16.04

MAINTAINER cuong.ngo

RUN apt-get update && apt-get install -y --no-install-recommends apt-utils

RUN apt-get update && apt-get install -y language-pack-en

RUN locale-gen en_US.UTF-8

ENV LANGUAGE=en_US.UTF-8
ENV LC_ALL=en_US.UTF-8
ENV LC_CTYPE=UTF-8
ENV LANG=en_US.UTF-8
ENV TERM xterm


# Clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install "software-properties-common" (for the "add-apt-repository")
RUN apt-get update && apt-get install -y \
    software-properties-common libmcrypt-dev php-pear

# Add the "PHP 7" ppa
RUN add-apt-repository -y \
    ppa:ondrej/php

# Clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install PHP-CLI 7, some PHP extentions and some useful Tools with APT
RUN apt-get update && apt-get install -y --allow-remove-essential \
        php7.4-fpm \ 
        php7.4-cli \
        php7.4-dev \
        php7.4-phpdbg \
        php7.4-common \
        php7.4-curl \
        php7.4-json \
        php7.4-xml \
        php7.4-mbstring \
        php7.4-mysql \
        php7.4-pgsql \
        php7.4-sqlite \
        php7.4-sqlite3 \
        php7.4-zip \
        php7.4-imagick \
        sqlite3 \
        libsqlite3-dev \
        git \
        curl \
        vim \
        nano \
        php7.4-gd \
        npm \
        telnetd \
    && apt-get clean


# Install NodeJs 12x
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash
RUN apt-get install -y nodejs

# Install Socket.io Client & Laravel echo server
RUN npm install --save socket.io-client@2.3.0 laravel-echo-server
RUN npm install

# Install Redis server
RUN apt-get install -y redis-server
RUN redis-server --daemonize yes

# Install composer
RUN curl -s http://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Configure Nginx
RUN apt-get -yqq update; apt-get -yqq install nginx
ADD default.conf /etc/nginx/conf.d/default.conf
ADD nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /var/www/project
WORKDIR /var/www/project

RUN usermod -u 1000 www-data

CMD service php7.4-fpm start && nginx

EXPOSE 80
