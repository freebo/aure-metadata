#!/bin/bash

apt-get update && apt-get install -y git apache2 php7.0 libapache2-mod-php7.0 stress

cd /var/www/html && git clone http://github.com/freebo/azure-metadata

cronjob="*/2 * * * * cd /var/www/html && git pull https://github.com/freebo/azure-metadata.git > /dev/null 2>&1" (crontab -u root -l; echo "${cronjob}" ) | crontab -u ${root} - crontab -l

crontab -l | { cat; echo "*/2 * * * * cd /var/www/html/azure-metadata && git pull https://github.com/freebo/azure-metadata.git"; } |crontab -


