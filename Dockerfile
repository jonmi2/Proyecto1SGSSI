FROM php:7.2.2-apache

# Instala herramientas necesarias y extensiones PHP
RUN apt-get update && apt-get install -y openssl && docker-php-ext-install mysqli

# Genera el certificado SSL autofirmado
RUN mkdir /etc/ssl/certs && mkdir /etc/ssl/private && \
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/apache-selfsigned.key \
    -out /etc/ssl/certs/apache-selfsigned.crt \
    -subj "/C=US/ST=State/L=City/O=Organization/OU=Department/CN=localhost"

# Copia el archivo de configuración de Apache
COPY apache-ssl.conf /etc/apache2/sites-available/000-default.conf

# Habilita módulos de Apache necesarios para SSL
RUN a2enmod ssl rewrite && \
    a2ensite 000-default.conf && \
    service apache2 restart
