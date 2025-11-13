FROM php:8.2-apache

# Instalar extensiones MySQL CRÍTICAS
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar extensiones
RUN docker-php-ext-enable mysqli pdo_mysql

# Copiar todo el código
COPY . /var/www/html/

# Configurar puerto para Railway
RUN echo 'Listen ${PORT}' > /etc/apache2/ports.conf
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's/:80/:${PORT}/g' /etc/apache2/sites-available/000-default.conf

# Dar permisos
RUN chmod -R 755 /var/www/html/

CMD ["apache2-foreground"]
