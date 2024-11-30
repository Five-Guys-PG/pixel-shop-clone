FROM prestashop/prestashop:1.7.8

COPY ./ssl/key.pem /var/www/prestashop/.ssl/key.pem
COPY ./ssl/cert.pem /var/www/prestashop/.ssl/cert.pem

COPY ./ssl/default-ssl.conf /etc/apache2/sites-available/000-default-ssl.conf
RUN a2enmod ssl

RUN ln -s /etc/apache2/sites-available/000-default-ssl.conf /etc/apache2/sites-enabled/000-default-ssl.conf

RUN service apache2 restart
