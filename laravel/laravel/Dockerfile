FROM php:7.2-fpm

# Copy composer.lock and composer.json
#COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
        build-essential \
        mariadb-client \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        locales \
        zip \
        jpegoptim optipng pngquant gifsicle \
        vim \
        unzip \
        git \
        curl \
        nginx \
        libldap2-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd
RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu; 
RUN docker-php-ext-install ldap 

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN mkdir -p /var/www/itam-app-gui
COPY nginx.conf /etc/nginx/sites-available/default

RUN mkdir -p /app
COPY code /app/
RUN mkdir -p /var/www/ensystemconfig/
RUN chown www-data:www-data /var/www/ensystemconfig/ -R
RUN chmod 777 /var/www/ensystemconfig/
RUN chown www-data:www-data /app/ -R
WORKDIR /var/www


# Copy ioncube encoder configuration and extension files
#COPY ioncube/*.* /usr/local/lib/php/extensions/no-debug-non-zts-20151012/
#COPY 20-ioncube.ini /usr/local/etc/php/conf.d/docker-php-ext-20ioncube.ini
#CMD ["service nginx start"] 
