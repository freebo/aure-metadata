FROM phpdockerio/php72-fpm

MAINTAINER Mike Freeman <freeman.mj@gmail.com>

# install git apache etc

RUN apt-get update
RUN apt-get install -y -f git apache2 libapache2-mod-php stress

RUN rm -f /var/www/html/index.html

RUN cd /var/www/html && git clone https://github.com/freebo/azure-metadata.git .

EXPOSE 80
EXPOSE 443

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]