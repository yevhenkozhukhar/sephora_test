# Simple Order API 

# Requirements to project

Using docker:
 - Docker
 - Docker Compose

Without docker:
- PHP 8.2
- MySql 8.0
- Composer 2

## Description
Provides simple API for work with products

## Getting Started

Provide instructions on how to get your project up and running. Include any prerequisites, installation steps.

### Installation

Provide step-by-step instructions on how to install and configure your project.

```
git clone git@github.com:yevhenkozhukhar/sephora_test.git
```

### Installation via make file(recommended - easy to use)

Should have before installation docker and docker-compose

```
make deps
```

### Alternative installation via docker-compose by steps

Start containers:
```
docker-compose up -d
```

Install composer dependencies via docker
```
docker-compose exec apache-service composer install
```

Create database and fill with migrations

```
docker-compose exec apache-service bin/console doctrine:database:create --if-not-exists
docker-compose exec apache-service bin/console doctrine:database:create --if-not-exists --env=test
docker-compose exec apache-service bin/console doctrine:migrations:migrate
docker-compose exec apache-service bin/console doctrine:migrations:migrate --env=test
```

### Start or down project make command(alternative to docker-compose)

`make start` - same as `docker-compose up -d` - start containers

`make down` - same as `docker-compose down` - down containers

### Load fixtures(demo data) via make
```
fixture-load
```

### Unit tests

Run via make
```
make unit
```

Command for test
```
./bin/phpunit
```

### Project url running with docker-compose 
```
http://localhost:8003
```

### Api documentation (Swagger UI)

```
http://localhost:8003/api/doc
```
