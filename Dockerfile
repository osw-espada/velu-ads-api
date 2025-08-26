FROM surnet/alpine-wkhtmltopdf:3.21.3-024b2b2-full AS wkhtmltopdf
FROM alpine:3.22
LABEL Maintainer="Ken Domingo <ken@pmti.biz>"
LABEL Description="Nginx 1.28 & PHP 8.3 based on Alpine Linux."

ARG worker="false"

# Install dependencies for wkhtmltopdf
RUN apk add --no-cache \
    libstdc++ \
    libx11 \
    libxrender \
    libxext \
    libssl3 \
    ca-certificates \
    fontconfig \
    freetype \
    ttf-dejavu \
    ttf-droid \
    ttf-freefont \
    ttf-liberation \
    # more fonts
  && apk add --no-cache --virtual .build-deps \
    msttcorefonts-installer \
  # Install microsoft fonts
  && update-ms-fonts \
  && fc-cache -f \
  # Clean up when done
  && rm -rf /tmp/* \
  && apk del .build-deps

# Copy wkhtmltopdf files from docker-wkhtmltopdf image
COPY --from=wkhtmltopdf /bin/wkhtmltopdf /bin/wkhtmltopdf
COPY --from=wkhtmltopdf /bin/wkhtmltoimage /bin/wkhtmltoimage
COPY --from=wkhtmltopdf /lib/libwkhtmltox* /lib/

# Install packages and remove default server definition
RUN apk --no-cache add \
  curl \
  nginx \
  php \
  php-ctype \
  php-curl \
  php-dom \
  php-fpm \
  php-gd \
  php-intl \
  php-json \
  php-mbstring \
  php-mysqli \
  php-opcache \
  php-openssl \
  php-phar \
  php-session \
  php-xml \
  php-xmlreader \
  php-xmlwriter \
  php-simplexml \
  php-zlib \
  php-exif \
  php-fileinfo \
  php-tokenizer \
  php-pdo \
  php-pdo_mysql \
  php-pdo_pgsql \
  php-pgsql \
  php-zip \
  php-iconv \
  php-pecl-imagick \
  php-sodium \
  supervisor \
  libpq-dev \
  imagemagick \
  imagemagick-dev

# Configure nginx
COPY docker/config/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY docker/config/fpm-pool.conf /etc/php/php-fpm.d/www.conf
COPY docker/config/php.ini /etc/php/conf.d/custom.ini

# Configure supervisord
COPY docker/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/config/supervisord-worker.conf /etc/supervisor/conf.d/supervisord-worker.conf

COPY docker/worker.sh /worker.sh

RUN if [ "$worker" = "true" ]; then \
    sed -i 's/\r//' /worker.sh && \
    chmod +x /worker.sh && \
    /worker.sh; \
  fi

# Setup document root
RUN mkdir -p /var/www/html

# Ensure permissions are set to nobody:nobody (use colon, not dot)
RUN chown -R nobody:nobody /var/www/html && \
  chown -R nobody:nobody /run && \
  chown -R nobody:nobody /var/lib/nginx && \
  chown -R nobody:nobody /var/log/nginx

# Install PHP Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Switch to use a non-root user from here on
USER nobody

# Add application (ensure permissions owner nobody, group nobody)
WORKDIR /var/www/html
COPY --chown=nobody:nobody . .

RUN composer install --no-dev

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Optionally, add a healthcheck (uncomment if required)
# HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping
