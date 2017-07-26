# azure-metadata
apt-get install -y git apache2 php7.0 libapache2-mod-php7.0 stress

cd /var/www/html && git clone http://github.com/freebo/azure-metadata

echo "* * * * * cd /var/www/html/azure-metadata && git pull http://github.com/freebo/azure-metadata ">>/var/spool/cron/crontabs/root

Inspired by alphamusk's AWS metatdata page

