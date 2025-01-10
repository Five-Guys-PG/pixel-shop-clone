#!/bin/bash

envsubst < /app/parameters.php > /var/www/html/app/config/parameters.php

sleep 20
mysql -h${DB_SERVER} -u${DB_USER} -p${DB_PASSWD}  ${DB_NAME} < /app/backup

/tmp/docker_run.sh "$@"
