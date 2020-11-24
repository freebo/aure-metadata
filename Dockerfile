FROM php:7.2-apache

MAINTAINER Mike Freeman <freeman.mj@gmail.com>

RUN apt-get update

RUN rm -f /var/www/html/index.html

RUN cd /var/www/html && git clone https://github.com/freebo/azure-metadata.git .

EXPOSE 80
EXPOSE 443

CMD [ "executable" ] ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
