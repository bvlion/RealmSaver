FROM centos:7

RUN yum -y install epel-release
RUN rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm

RUN yum -y install --enablerepo=remi,remi-php70 php php-devel php-mbstring php-pdo php-gd php-xml php-mcrypt php-pecl-zip php-mysqlnd

RUN sed -i -e "s|^;date.timezone =.*$|date.timezone = Asia/Tokyo|" /etc/php.ini
RUN echo -e "\nxdebug.remote_enable=1" "\nxdebug.remote_autostart=1" >> /etc/php.ini

RUN yum -y install httpd

COPY httpd.conf /etc/httpd/conf.d/tmp.conf

EXPOSE 80
CMD ["/usr/sbin/apachectl", "-D", "FOREGROUND"]