# pixel-shop-clone


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
- Execute [shop/db-backups/db_backup_1.sql](shop/db-backups/db_backup_1.sql)
