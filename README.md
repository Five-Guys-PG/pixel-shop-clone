# Pixel Shop Clone

A clone of the original [Pixel Shop](https://pixel-shop.pl) built using PrestaShop v1.7.8.11.

---

## 🚀 How to Run Locally

### Prerequisites
- **Docker Engine**: Ensure Docker Engine is installed on your machine. Follow the [installation instructions](https://docs.docker.com/engine/install/).

---

### 1. Clone the Repository
```bash
git clone https://github.com/Five-Guys-PG/pixel-shop-clone.git
cd pixel-shop-clone
```

### Create your .env file
In the repo there is .env.sample file. You can use it as a template for your own .env file
```
cp .env.sample .env
```

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

### HTTPS intructions
To be able to use https on this website you need to complete following steps:

1. Generate SSL certificate
```
openssl req -x509 -newkey rsa:4096 -keyout key.pem -out cert.pem -days 365 -nodes
```

2. Add created files (key.pem and cert.pem) to ssl/ directory
3. Enable SSL in admin panel if it's not


## 👥 Authors
- [Danylo Zakharchenko](https://github.com/zakh-d)
- [Vitalii Shapovalov](https://github.com/vetall7)
- [Ruslan Rbaadanov](https://github.com/R-Ohman)
- [Artem Dychenko](https://github.com/artemDychenko)
