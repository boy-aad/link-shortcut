# Utilise une image officielle avec PHP et Apache
FROM php:8.2-apache

# Copie tout le contenu de ton projet dans le dossier du serveur
COPY . /var/www/html/

# Active le module rewrite d'Apache (utile pour les routes)
RUN a2enmod rewrite

# Donne les bons droits
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose le port utilisé par Apache
EXPOSE 80
