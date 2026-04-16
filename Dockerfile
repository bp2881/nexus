# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies (including Python for DB setup)
RUN apt-get update && apt-get install -y \
    python3 \
    sqlite3 \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite for friendly URLs
RUN a2enmod rewrite

# Enable .htaccess support (AllowOverride All)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Set the working directory to Apache's default web root
WORKDIR /var/www/html

# Copy the application source code into the container
# Use .dockerignore to exclude unnecessary files
COPY . .

# Ensure the db and cache directories exist and are writable by Apache
RUN mkdir -p db cache && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/db /var/www/html/cache

# Run the database setup script to initialize the SQLite database
# We do this during the build phase so the image is ready-to-use
RUN python3 python/setup_db.py && \
    chown www-data:www-data db/nexus.db && \
    chmod 664 db/nexus.db

# Expose port 80 for the web server
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
