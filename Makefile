PHP=docker-compose exec apache-service
#PHP=docker-compose exec php-service php # nginx

start:
	docker-compose up -d

install:
	$(PHP) composer install

db:
	$(PHP) bin/console doctrine:database:create --if-not-exists
	$(PHP) bin/console doctrine:database:create --env=test --if-not-exists
	$(MAKE) migrate

redb:
	$(PHP) bin/console doctrine:database:drop --if-exists --force
	$(PHP) bin/console doctrine:database:drop --env=test --if-exists --force
	$(MAKE) db

migrate:
	$(PHP) bin/console doctrine:migrations:migrate --no-interaction
	$(PHP) bin/console doctrine:migrations:migrate --env=test --no-interaction

down:
	docker-compose down

composer-req:
	$(PHP) composer require $(args)

phpshell:
	docker-compose exec apache-service bash
	#docker-compose exec php-service bash # nginx

myshell:
	docker-compose exec mysql-service mysql -u root -psecret


unit:
	$(PHP) bin/phpunit $(args)