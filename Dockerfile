FROM php:8.4-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install SQLite extensions
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

# Set the working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Rename example.htaccess to .htaccess if it exists
RUN if [ -f /var/www/html/example.htaccess ]; then \
        mv /var/www/html/example.htaccess /var/www/html/.htaccess; \
    fi

# Update .htaccess for root installation (no subfolder)
RUN if [ -f /var/www/html/.htaccess ]; then \
        sed -i '/RewriteBase/d' /var/www/html/.htaccess; \
    fi

# Create data directory and set permissions
RUN mkdir -p /var/www/html/data && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Configure Apache to allow .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set ServerName to suppress warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
