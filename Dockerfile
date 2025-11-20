FROM wordpress:latest

RUN apt-get update && apt-get install -y \
    curl \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/wp-content/themes/invirowp-theme

RUN chown -R www-data:www-data /var/www/html/wp-content/themes/invirowp-theme

EXPOSE 80
