#!/bin/bash

envsubst < /app/parameters.php > /var/www/html/app/config/parameters.php

/tmp/docker_run.sh "$@"
