# Pixel Store Clone

### [Original Site](https://pixel-shop.pl)

## Running using docker-compose

- Go to the ```shop``` directory
```
cd shop
```

- Create ```.env``` file 

Threre is ```.env.sample``` file which can be used as a template 
for your environment file 
```
cp .env.sample .env
```
Then edit .env file and set secure password for db and admin url

- Start docker containers

```
docker compose up -d
```
If you are using Linux system you might need superuser priviliges (just add sudo before the command)

### If it's first time you running the shop you need to load data to db
- Add execute rights for load_backup script
```
chmod u+x ./scripts/load_backup.sh
```
- Load backup from ```db-backups``` directory
```
./scripts/load_backup.sh ./db-backups/db_backup_1.sql
```
