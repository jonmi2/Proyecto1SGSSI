FROM php:7.2.2-apache

# Actualizamos los repositorios para que apunten a archive.debian.org, eliminando entradas no válidas
RUN sed -i '/stretch-updates/d' /etc/apt/sources.list && \
    sed -i '/stretch\/updates/d' /etc/apt/sources.list && \
    sed -i 's|http://deb.debian.org|http://archive.debian.org|g' /etc/apt/sources.list && \
    sed -i 's|http://security.debian.org|http://archive.debian.org|g' /etc/apt/sources.list && \
    apt-get update -o Acquire::Check-Valid-Until=false && \
    apt-get install -y openssl && \
    docker-php-ext-install mysqli

# Deshabilitar el encabezado X-Powered-By de PHP
RUN echo "expose_php = Off" >> /usr/local/etc/php/php.ini

# Genera el certificado SSL autofirmado
RUN mkdir -p /etc/ssl/certs /etc/ssl/private && \
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/apache-selfsigned.key \
    -out /etc/ssl/certs/apache-selfsigned.crt \
    -subj "/C=US/ST=State/L=City/O=Organization/OU=Department/CN=localhost"

# Habilita módulos de Apache necesarios para SSL y headers
RUN a2enmod ssl rewrite headers

# Copia el archivo de configuración de Apache
COPY apache-ssl.conf /etc/apache2/sites-available/000-default.conf

# Habilita el sitio y recarga Apache
RUN a2ensite 000-default.conf && \
    service apache2 reload

