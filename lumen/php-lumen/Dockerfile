FROM php:7.2-fpm
MAINTAINER rajnesh.kumar@esds.co.in

RUN apt-get update && apt-get install -y zip unzip git libmcrypt-dev libldap2-dev libpng-dev python python-pip build-essential python-mysqldb tree mariadb-client vim
#RUN  docker-php-ext-install mcrypt pdo_mysql
RUN docker-php-ext-install mysqli pdo_mysql
RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu; 
RUN docker-php-ext-install ldap gd zip

COPY pip-requirments.txt /root/
RUN pip install -r /root/pip-requirments.txt
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN mkdir -p /var/www/itam-services
COPY lumen /var/www/html/
RUN chown www-data:www-data /var/www/html -R
WORKDIR /var/www

# Copy ioncube encoder configuration and extension files
#COPY ioncube/*.* /usr/local/lib/php/extensions/no-debug-non-zts-20151012/
#COPY 20-ioncube.ini /usr/local/etc/php/conf.d/docker-php-ext-20ioncube.ini 
