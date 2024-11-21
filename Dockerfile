FROM php:7.2.2-apache

# Cambiar los repositorios de Debian a una versión más reciente (porq me da problemas docker)
RUN sed -i 's/stretch/buster/g' /etc/apt/sources.list

# Instalar gnupg para gestionar claves GPG (porq me da problemas docker)
RUN apt-get update && apt-get install -y gnupg

# Agregar las claves GPG faltantes (porq me da problemas docker)
RUN apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 112695A0E562B32A 54404762BBB6E853 648ACFD622F3D138 0E98404D386FA1D9 DCC9EFBF77E11517

# Instala herramientas necesarias y extensiones PHP
RUN apt-get update && apt-get install -y openssl && docker-php-ext-install mysqli


# Deshabilita el encabezado X-Powered-By de PHP
RUN echo "expose_php = Off" >> /usr/local/etc/php/php.ini

# Genera el certificado SSL autofirmado
RUN mkdir /etc/ssl/certs && mkdir /etc/ssl/private && \
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/apache-selfsigned.key \
    -out /etc/ssl/certs/apache-selfsigned.crt \
    -subj "/C=US/ST=State/L=City/O=Organization/OU=Department/CN=localhost"

 # Habilita módulos de Apache necesarios para SSL y headers
RUN a2enmod ssl rewrite headers

# Copia el archivo de configuración de Apache
COPY apache-ssl.conf /etc/apache2/sites-available/000-default.conf

# Habilita el sitio y reinicia Apache
RUN a2ensite 000-default.conf && \
    service apache2 restart
        
