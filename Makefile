.DEFAULT_GOAL := init

name?=exess

up:
	@docker-compose -p $(name) -f docker-compose.yml up -d --build

restart:
	@docker-compose -p $(name) -f docker-compose.yml restart

down:
	@docker-compose -p $(name) -f docker-compose.yml down --remove-orphans --volumes

init:
	@make up
	@make composer

env?=dev
init-db:
	@docker-compose exec -T --env APP_ENV=$(env) php /usr/local/bin/composer run init-db

composer:
	@docker-compose exec -T php composer install

composer-require:
	@docker-compose exec -T php composer require $(package)

package?=
composer-update:
	@docker-compose exec -T php composer update $(package)

lint:
	@docker-compose exec -T php composer run lint

test:
	@docker-compose exec -T php composer run test

test-debug:
	@docker-compose exec -T php XDEBUG_TRIGGER=1 composer run test

suite?=unit
codecept:
	@docker-compose exec -T php bin/codecept run $(suite)

codecept-debug:
	@docker-compose exec -T php XDEBUG_TRIGGER=1 bin/codecept run $(suite)

php-sh:
	@docker-compose exec php /bin/sh

cache-flush:
	@docker-compose exec -T php bin/console nova:cache:clear

