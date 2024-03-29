start:
	php artisan serve --host=0.0.0.0 --port=$(PORT)

setup:
	cp -n .env.example .env || true
	composer install
	npm ci
	php artisan key:generate
	npm run build

migrate:
	php artisan migrate:fresh --force --seed

validate:
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 app routes

lint-fix:
	composer exec phpcbf -- --standard=PSR12 app routes

test:
	php artisan test

test-coverage:
	XDEBUG_MODE=coverage php artisan test --coverage-clover build/logs/clover.xml