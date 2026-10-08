FROM wordpress:6-php8.3-apache

# Tainacan needs imagick/gd for thumbnails of museum images
RUN apt-get update \
 && apt-get install -y --no-install-recommends libmagickwand-dev ghostscript unzip curl \
 && pecl install imagick && docker-php-ext-enable imagick \
 && rm -rf /var/lib/apt/lists/*

# WP-CLI
RUN curl -fsSL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp \
 && chmod +x /usr/local/bin/wp
