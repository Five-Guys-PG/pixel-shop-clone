#!/bin/bash

# Check if an argument was supplied
if [ $# -eq 0 ]; then
  echo "No SQL file supplied. Usage: ./load_backup.sh <path_to_sql_file>"
  exit 1
fi

# Check if the file exists on the host system
if [ ! -f "$1" ]; then
  echo "The file '$1' does not exist."
  exit 1
fi

# Load environment variables from .env file
source .env

# Copy the SQL file into the container
echo "Copying '$1' to the MySQL container..."
docker compose cp "$1" mysql:/tmp/backup.sql

# Check if the file was copied successfully
if [ $? -ne 0 ]; then
  echo "Failed to copy the file to the container."
  exit 1
fi

# Execute the SQL file inside the container
echo "Executing the SQL file inside the container..."
docker compose exec mysql bash -c "mysql -u${DB_USER} -p${DB_PASSWD} ${DB_NAME} < /tmp/backup.sql"

# Check if the SQL execution was successful
if [ $? -ne 0 ]; then
  echo "Failed to execute the SQL file."
  exit 1
fi

echo "SQL file executed successfully!"
