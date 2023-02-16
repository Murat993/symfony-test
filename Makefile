init: docker-build docker-up migrate

docker-up:
	docker-compose up -d

docker-build:
	docker-compose build

migrate:
	docker-compose run --rm php php bin/console doctrine:migrations:migrate  --no-interaction
