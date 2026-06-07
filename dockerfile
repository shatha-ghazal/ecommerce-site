FROM php:8.2-apache

# Install PostgreSQL + MySQL drivers (safe to include both)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql mysqli

# Enable Apache rewrite (optional but common)
RUN a2enmod rewrite

COPY . /var/www/html/

EXPOSE 80
