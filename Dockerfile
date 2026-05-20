FROM php:8.5-cli
RUN docker-php-ext-install mysqli
WORKDIR /var/www/html
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "index.php"]
