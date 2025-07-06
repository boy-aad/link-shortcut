FROM php:8.2-apache

# Installer l’extension PDO PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Copier tout le projet dans /var/www/html
COPY . /var/www/html/

# Donner les droits au serveur web
RUN chown -R www-data:www-data /var/www/html

# Activer mod_rewrite si besoin (optionnel)
RUN a2enmod rewrite
