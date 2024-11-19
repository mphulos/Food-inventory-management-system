# Use official PHP 7.3 with Apache image as base
FROM php:7.3-apache

# Enable Apache mod_rewrite for clean URLs
RUN a2enmod rewrite

# Set the working directory in the container
WORKDIR /var/www/html

# Copy your PHP application files to the container's working directory
COPY . .

# Install PHP dependencies (use composer)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer (if not already present)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies via Composer
RUN composer install --no-dev --prefer-dist

# Expose Apache port
EXPOSE 80
