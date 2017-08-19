#!/bin/bash

apt-get update && apt-get install -y git apache2 php7.0 libapache2-mod-php7.0 stress

rm -f /var/www/html/index.html

cd /var/www/html && git clone http://github.com/freebo/azure-metadata .


