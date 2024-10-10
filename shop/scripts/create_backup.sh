#!/bin/bash


# Check if an argument was supplied
if [ $# -eq 0 ]; then
    echo "No SQL file supplied. Usage: ./create_backup.sh <path_to_output_file>"
    exit 1
fi


# Check if the file exists on the host system
if [ -f "$1" ]; then
    echo "The file '$1' exists. Do you want to override it? (y/n)"
    read -r response
    if [[ "$response" != "y" ]]; then
        echo "Backup canceled."
        exit 1
    fi
fi


source .env

docker compose exec mysql mysqldump -u root -p$DB_PASSWD $DB_NAME > $1
