.PHONY: up down sh migrate seed test logs

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose up -d --build

start:
	docker compose up -d --build
	docker compose exec app composer install --prefer-dist --no-interaction
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan migrate:fresh --seed
	docker compose exec app npm ci --no-audit --no-fund;
	docker compose exec app npm run dev
	docker compose exec app php artisan storage:link || true

restart:
	docker compose down && docker compose up -d

rebuild:
	docker compose down && docker compose up -d --build

shell:
	docker compose exec app sh

bash:
	docker compose exec app bash

migrate:
	docker compose exec app php artisan migrate

seed:
	docker compose exec app php artisan db:seed

test:
	docker compose exec app php artisan test

test-coverage:
	docker compose exec app php artisan test --coverage

test-coverage-html:
	docker compose exec app vendor/bin/phpunit --coverage-html coverage-report/

logs:
	docker compose logs -f
