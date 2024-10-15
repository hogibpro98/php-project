# Use the official PHP 7.4 image as the base
FROM php:7.4-apache

# Install MySQL client and other dependencies
RUN apt-get update && \
    apt-get install -y \
    default-mysql-client \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip unzip \
    libzip-dev \
    curl && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql mysqli zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Xdebug
RUN pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug

# Add Xdebug configuration file
ADD config/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Node.js 18 and npm
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Verify Node.js and npm installation
RUN node -v && npm -v

# Set working directory to /var/www/html
WORKDIR /var/www/html

# Copy configuration files to /var/www
COPY .php-cs-fixer.dist.php /var/www/
COPY composer.json /var/www/
COPY eslint-project-config.js /var/www/
COPY package.json /var/www/

# Run Composer install
RUN composer install --working-dir=/var/www

# Run npm install
RUN npm install --working-dir=/var/www

# Change ownership of /var/www/html to www-data and set permissions
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \;

# Expose port 80
EXPOSE 80