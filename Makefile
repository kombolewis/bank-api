.PHONY: down up migrate fresh migratereset migratefresh

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
	
passportinstall:
	@./vendor/bin/sail artisan passport:install
