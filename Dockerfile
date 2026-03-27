FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive
ENV APACHE_DOCUMENT_ROOT=/var/www/html/web

# Install base packages and add PHP PPA
RUN apt-get update && apt-get install -y \
    software-properties-common \
    curl \
    unzip \
    && add-apt-repository ppa:ondrej/php -y \
    && apt-get update

# Install Apache + PHP 8.4 with extensions
RUN apt-get install -y \
    apache2 \
    libapache2-mod-php8.4 \
    php8.4-cli \
    php8.4-mysql \
    php8.4-gd \
    php8.4-intl \
    php8.4-zip \
    php8.4-xml \
    php8.4-mbstring \
    php8.4-curl \
    php8.4-bcmath \
    php8.4-opcache \
    php8.4-exif \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite

# Set Apache document root to Bedrock's web directory
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' \
    /etc/apache2/apache2.conf

# PHP development settings
RUN sed -i 's/display_errors = Off/display_errors = On/' /etc/php/8.4/apache2/php.ini \
    && sed -i 's/display_startup_errors = Off/display_startup_errors = On/' /etc/php/8.4/apache2/php.ini \
    && sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 64M/' /etc/php/8.4/apache2/php.ini \
    && sed -i 's/post_max_size = 8M/post_max_size = 64M/' /etc/php/8.4/apache2/php.ini \
    && sed -i 's/memory_limit = 128M/memory_limit = 256M/' /etc/php/8.4/apache2/php.ini \
    && sed -i 's/max_execution_time = 30/max_execution_time = 600/' /etc/php/8.4/apache2/php.ini \
    && sed -i 's/;max_input_vars = 1000/max_input_vars = 3000/' /etc/php/8.4/apache2/php.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install WP-CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

WORKDIR /var/www/html

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]
