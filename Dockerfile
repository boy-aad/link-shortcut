FROM php:8.2-apache

# Installer les paquets nécessaires pour PDO PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Copier les fichiers du projet dans le conteneur
COPY . /var/www/html/

# Donner les bons droits
RUN chown -R www-data:www-data /var/www/html

# Activer mod_rewrite (si besoin)
RUN a2enmod rewrite
