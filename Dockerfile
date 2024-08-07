# Use the official PHP image with FPM
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html

# Install Composer if needed
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Uncomment the following line if you need to run composer install
# RUN composer install

# Expose port 9000 for PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
