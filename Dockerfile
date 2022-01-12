FROM php:7.4-apache

ARG LOCAL
ARG DEBIAN_FRONTEND=noninteractive

# Get repository and install wget and vim
RUN apt-get update && apt-get install --no-install-recommends -y \
    apt-utils \
    wget \
    git \
    unzip \
    libzip-dev

# Install PHP extensions deps
RUN apt-get update \
    && apt-get install --no-install-recommends -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        zlib1g-dev \
        libicu-dev \
        g++ \
        unixodbc-dev \
        libxml2-dev \
        libaio-dev \
        libgearman-dev \
        libmemcached-dev \
        freetds-dev \
	    libssl-dev \
        libonig-dev \
	    openssl

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP extensions
RUN docker-php-ext-configure gd
RUN docker-php-ext-configure pdo_dblib --with-libdir=/lib/x86_64-linux-gnu
RUN docker-php-ext-install \
            mbstring \
            intl \
            gd \
            mysqli \
            pdo_mysql \
            pdo_dblib \
            soap \
            sockets \
            zip \
            pcntl \
            ftp \
            bcmath

# Configure PHP for Cloud Run.
# Precompile PHP code with opcache.
RUN set -ex; \
  { \
    echo "; Cloud Run enforces memory & timeouts"; \
    echo "memory_limit = -1"; \
    echo "max_execution_time = 0"; \
    echo "; File upload at Cloud Run network limit"; \
    echo "upload_max_filesize = 32M"; \
    echo "post_max_size = 32M"; \
    echo "; Configure Opcache for Containers"; \
    echo "opcache.enable = On"; \
    echo "opcache.validate_timestamps = Off"; \
    echo "; Configure Opcache Memory (Application-specific)"; \
    echo "opcache.memory_consumption = 32"; \
  } > "$PHP_INI_DIR/conf.d/cloud-run.ini"

# Memcached
RUN apt-get install --no-install-recommends -y \
    memcached \
    libmemcached-tools
RUN pecl install memcached && docker-php-ext-enable memcached
COPY start-memcached /usr/local/bin/start-memcached
RUN chmod +x /usr/local/bin/start-memcached

# Copy in custom code from the host machine.
WORKDIR /var/www/html
COPY ./src /var/www/html

# Use the PORT environment variable in Apache configuration files.
# https://cloud.google.com/run/docs/reference/container-contract#port
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# download and install cloud_sql_proxy
RUN wget https://dl.google.com/cloudsql/cloud_sql_proxy.linux.amd64 -O /usr/local/bin/cloud_sql_proxy && \
    chmod +x /usr/local/bin/cloud_sql_proxy

# custom entrypoint
COPY ./cloud-run-entrypoint.sh /usr/local/bin/

RUN cd /var/www/html && \
    composer install --no-plugins --no-scripts

# Clean repository
RUN apt-get clean \
  && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

RUN if [ "$LOCAL" = "1" ] ; \
    then pecl install xdebug && docker-php-ext-enable xdebug; \
    else docker-php-ext-install -j "$(nproc)" opcache; \
   fi

ENV APACHE_DOCUMENT_ROOT=/var/www/html/api
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

ENTRYPOINT ["start-memcached", "cloud-run-entrypoint.sh", "apache2-foreground"]