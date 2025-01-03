FROM httpd:2.4.62-alpine

RUN rm -rf /var/www/html/

COPY ./prestashop/ /var/www/html/

COPY ./ssl/key.pem /var/www/prestashop/.ssl/key.pem
COPY ./ssl/cert.pem /var/www/prestashop/.ssl/cert.pem
COPY ./ssl/default-ssl.conf /usr/local/apache2/conf/httpd.conf
RUN a2enmod ssl

