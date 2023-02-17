init: docker-build docker-up composer-install migrate

docker-up:
	docker-compose up -d

docker-build:
	docker-compose build

composer-install:
	docker-compose run --rm php composer install

migrate:
	docker-compose run --rm php php bin/console doctrine:migrations:migrate  --no-interaction
