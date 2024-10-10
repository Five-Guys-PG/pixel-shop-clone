# Pixel Store Clone

### [Original Site](https://pixel-shop.pl)

## Running using docker-compose

- Go to the ```shop``` directory
```
cd shop
```

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
