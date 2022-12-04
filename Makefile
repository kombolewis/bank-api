.PHONY: up down migrate fresh migratereset migratefresh migrateseed test key

up:
	@./vendor/bin/sail up 

down:
	@./vendor/bin/sail down 

migrate:
	@./vendor/bin/sail artisan migrate 

migratereset:
	@./vendor/bin/sail artisan migrate:reset

migratefresh:
	@./vendor/bin/sail artisan migrate:fresh 
	
migrateseed:
	@./vendor/bin/sail artisan migrate:refresh --seed
	
passportinstall:
	@./vendor/bin/sail artisan passport:install

test:
	@./vendor/bin/sail phpunit

key:
	@./vendor/bin/sail artisan key:generate

saildetach:
	@./vendor/bin/sail up -d

setup:
	php -r "file_exists('.env') || copy('.env.example', '.env');"
	composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist  --ignore-platform-reqs
	@./vendor/bin/sail up -d
	@./vendor/bin/sail artisan key:generate
	@./vendor/bin/sail artisan migrate:refresh --seed
	@./vendor/bin/sail artisan passport:install
	# @./vendor/bin/sail down
	# @./vendor/bin/sail up -d
	@chmod -R 777 storage bootstrap/cache
	@touch database/database.sqlite
	@echo 'DONE'
