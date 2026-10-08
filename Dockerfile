FROM wordpress:7-php8.3-apache

# Tainacan needs imagick/gd for thumbnails of museum images. The base image already
# ships and enables imagick; ghostscript adds PDF thumbnails.
RUN apt-get update \
 && apt-get install -y --no-install-recommends ghostscript unzip curl \
 && rm -rf /var/lib/apt/lists/*

# WP-CLI
RUN curl -fsSL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp \
 && chmod +x /usr/local/bin/wp
