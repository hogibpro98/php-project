# Sử dụng image PHP chính thức làm base
FROM php:7.4-apache

# Cài đặt MySQL client và các phụ thuộc khác
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

# Kích hoạt Apache mod_rewrite
RUN a2enmod rewrite

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Cài đặt Xdebug
RUN pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug

# Thêm file cấu hình Xdebug
ADD config/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Cài đặt Node.js 18 và npm
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Kiểm tra cài đặt Node.js và npm
RUN node -v && npm -v

# Đặt thư mục làm việc
WORKDIR /var/www/html

# Đổi chủ sở hữu thư mục về www-data (người dùng Apache) và cấp quyền 755
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \;

# Mở cổng 80
EXPOSE 80
