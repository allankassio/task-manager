FROM php:8.2-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set public folder as DocumentRoot
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy source code into the container
COPY . .

# Set build-time environment variable
ARG APP_ENV=prod
ENV APP_ENV=${APP_ENV}

# Install dependencies
RUN composer install --no-interaction

# Run PHPUnit tests in dev environment and show output
RUN if [ "$APP_ENV" = "dev" ]; then \
      echo "Running PHPUnit tests (APP_ENV=dev)..." && \
      ./vendor/bin/phpunit --colors=always || (echo 'PHPUnit tests failed' && exit 1); \
    else \
      echo "Skipping tests (APP_ENV=$APP_ENV)"; \
    fi

# Generate Swagger documentation
RUN ./vendor/bin/openapi --bootstrap vendor/autoload.php --output public/swagger.json src/ || true

# Run Apache by default
CMD ["apache2-foreground"]
