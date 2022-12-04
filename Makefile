.PHONY: down up migrate fresh migratereset migratefresh migrateseed test

down:
	@./vendor/bin/sail down 

up:
	@./vendor/bin/sail up 

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