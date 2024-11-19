# Use the official PHP 7.3 with Apache image as the base image
FROM php:7.3-apache

# Set environment variable for Google Cloud authentication
# The 'GOOGLE_APPLICATION_CREDENTIALS' will be passed as an environment variable in docker-compose or Docker run
ENV GOOGLE_APPLICATION_CREDENTIALS=/path/to/your/service-account-key.json  # Path to service account key

# Install required PHP extensions and dependencies for the application
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Install the Google Cloud SDK (for interacting with Google Cloud Storage or other GCP services)
RUN curl -sSL https://packages.cloud.google.com/apt/doc/apt-key.gpg | apt-key add - \
    && echo "deb https://packages.cloud.google.com/apt cloud-sdk main" | tee -a /etc/apt/sources.list.d/google-cloud-sdk.list \
    && apt-get update && apt-get install -y google-cloud-sdk

# Enable Apache mod_rewrite for URL rewrites
RUN a2enmod rewrite

# Copy the PHP application files into the container's web directory
COPY . /var/www/html/

# Set permissions for Apache to access the web files
RUN chown -R www-data:www-data /var/www/html

# Expose the default HTTP port
EXPOSE 80

# Set the default command to run Apache in the foreground
CMD ["apache2-foreground"]
