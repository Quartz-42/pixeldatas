.PHONY: start
start:
	docker compose up -d && symfony server:start

.PHONY: stop
stop:
	docker compose down && symfony server:stop	

.PHONY: php-stan
php-stan: 
	vendor\bin\phpstan analyse src templates --memory-limit=512M

.PHONY: php-cs
php-cs:
	 set "PHP_CS_FIXER_IGNORE_ENV=1" && .\vendor\bin\php-cs-fixer fix src
 
.PHONY : tailwind-build
tailwind-build:
	symfony console tailwind:build --watch

.PHONY : import-pokemons
import-pokemons:
	symfony console app:import-pokemons

.PHONY : test
test:
	php bin/phpunit