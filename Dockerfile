FROM php:8.5-cli-alpine
WORKDIR /var/www/html
COPY ./src /var/www/html
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "index.php"]