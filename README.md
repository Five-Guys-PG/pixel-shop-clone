# Pixel Shop Clone
Original [pixel-shop.pl](https://pixel-shop.pl)

## How to run it localy 

### Docker Engine
You need to have docker engine installed on your machine. Installation instructions [here](https://docs.docker.com/engine/install/) 

### Clone this repo
```
git clone https://github.com/Five-Guys-PG/pixel-shop-clone.git
```

### Create your .env file
In the repo there is .env.sample file. You can use it as a template for your own .env file
```
cp .env.sample .env
```
| If you have Windows: God bless you

Remember to change secrets in newly created .env file

### Up docker containers

Run this command to start docker containers
```
docker compose up -d
```

To stop containers use ```docker compose down```

### Load db backup
There are scripts for creating and loading db backups in ```scripts``` directory.

Add executable rights for them
```
chmod +x scripts/*
```

**Load backup**
```
./scripts/load_backup.sh <path to backup>
```
Backups are stored in ```backups``` dir.

**Create backup**
```
./scripts/create_backup.sh <path to output file>
```
