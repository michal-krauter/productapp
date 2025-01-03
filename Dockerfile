FROM php:7.4-fpm-buster

# Nastavení pracovního adresáře v Dockeru
WORKDIR /var/www/html

# Kopírování souborů z vaší aplikace do pracovního adresáře v Dockeru
COPY . .

# Instalace požadovaných závislostí
RUN apt-get update
RUN apt-get install -y --no-install-recommends libpng-dev libjpeg-dev libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libzip-dev unzip git

RUN rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-install pdo_mysql zip
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalace Composeru
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Nastavení oprávnění pro složku storage a cache
RUN mkdir -p /var/www/html/bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Instalace závislostí projektu pomocí Composeru
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Exponování portu pro aplikaci (aby byla dostupná mimo Docker)
EXPOSE 8000

# Start aplikace pomocí PHP built-in serveru
CMD php -S 0.0.0.0:8000 -t public
