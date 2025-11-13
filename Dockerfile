FROM php:8.2-apache

# Copiar todo tu código
COPY . /var/www/html/

# Configurar Apache para usar puerto dinámico
RUN echo 'Listen ${PORT}' > /etc/apache2/ports.conf
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf

# Exponer puerto
EXPOSE $PORT

CMD ["apache2-foreground"]
