# Utilise l'image officielle PHP avec Apache
FROM php:8.2-apache

# Installe l'extension PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copie tous les fichiers du projet dans le conteneur
COPY . /var/www/html/

# Active mod_rewrite d'Apache (utile pour les routes ou .htaccess)
RUN a2enmod rewrite

# Droits sur les fichiers
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose le port
EXPOSE 80
