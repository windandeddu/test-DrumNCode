## Requirements
```
1. composer
2. docker & docker-compose
```
## Quick Start
1. Install packages from composer
```bash
composer install
```

2. Configure your .env file
```bash
APP_URL=http://example-app.test

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=drumncode
DB_USERNAME=sail
DB_PASSWORD=password
```

3. Start docker containers
```bash
. vendor/bin/sail up -d
```
4. Run
```bash
. vendor/bin/sail key:generate
```

If you have errors:
```
SQLSTATE[HY000] [2002] No such file or directory
SQLSTATE[HY000] [2002] Connection refused
```
Please run
```bash
docker ps
```
And past mysql docker container name to DB_HOST

5. Add APP_URL from env to you hosts file (without http://)

7. Migrate and seed database
```bash
php artisan migrate --seed
```
