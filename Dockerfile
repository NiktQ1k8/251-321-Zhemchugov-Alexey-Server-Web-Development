FROM php:8.5-cli-alpine
WORKDIR /var/www/html
COPY ./src /var/www/html
CMD ["php", "-S", "0.0.0.0:8000"]