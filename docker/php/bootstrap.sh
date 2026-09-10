#!/usr/bin/env bash

#
# PHP container bootstrap script
#
# Do not modify this file as it is tracked by source control.
echo "Configuring Apache..."

sed -ri -e "s#\$\{APACHE_DOCUMENT_ROOT\}#/var/www/public#g" /etc/apache2/sites-enabled/*.conf
sed -ri -e "s#\$\{APACHE_DOCUMENT_DIRECTORY\}#/var/www/public#g" /etc/apache2/sites-enabled/*.conf

echo "Starting apache web server..."
/usr/local/bin/apache2-foreground

# A note on performance: It is typically better to keep this script
# light with no process-intensive operations as it will be run on every
# docker-compose up
