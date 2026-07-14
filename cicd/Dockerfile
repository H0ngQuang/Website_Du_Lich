FROM php:8.3-fpm
RUN apt-get update && apt-get install -y nginx supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
# Copy mọi thứ (vendor và public/build đã có sẵn nhờ Stage 2)
COPY . .

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# (Giữ nguyên phần cấu hình Nginx, Supervisor, Entrypoint như cũ của bạn)
# ...
EXPOSE 80
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]