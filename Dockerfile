# Focus Bio Marker Data Base
# ==========================
#
# Use by `docker image build`
#

# Basis
# -----
#
# Built on PHP 7.3 with Apache HTTPD running on Debian "Buster"

FROM php:7.3-apache-buster


# Application Layout
# ------------------

COPY --chown=www-data:www-data .htaccess index.php  /var/www/html/
COPY --chown=www-data:www-data app /var/www/html/app/
COPY --chown=www-data:www-data ldap /var/www/html/ldap/
COPY --chown=www-data:www-data lib /var/www/html/lib/
COPY --chown=www-data:www-data plugins /var/www/html/plugins/
COPY --chown=www-data:www-data rdf /var/www/html/rdf/
COPY --chown=www-data:www-data report /var/www/html/report/
COPY --chown=www-data:www-data vendors /var/www/html/vendors/
COPY share/php /usr/share/php/
COPY etc/bmdb.ini $PHP_INI_DIR/conf.d/
RUN \
    : Put a production PHP ini into "production" && \
    /bin/mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    : Enable the Apache rewrite module && \
    /usr/sbin/a2enmod rewrite && \
    : Add LDAP && \
    /usr/bin/apt-get update && \
    /usr/bin/apt-get install libldap2-dev -y && \
    /bin/rm -rf /var/lib/apt/lists/* && \
    /usr/local/bin/docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu && \
    /usr/local/bin/docker-php-ext-install ldap && \
    : Add MySQL access && \
    /usr/local/bin/docker-php-ext-install mysqli pdo pdo_mysql && :
