.PHONY: start
start:
	docker compose up -d && symfony server:start

.PHONY: stop
stop:
	docker compose down && symfony server:stop	

.PHONY: php-stan
php-stan: 
	php -d memory_limit=512M vendor\bin\phpstan analyse src templates

.PHONY: php-cs
php-cs:
	 set "PHP_CS_FIXER_IGNORE_ENV=1" && .\vendor\bin\php-cs-fixer fix src
 
.PHONY: tailwind-build
tailwind-build:
	symfony console tailwind:build --watch

.PHONY: import-pokemons
import-pokemons:
	php -d memory_limit=512M bin/console app:import-pokemons

.PHONY: test
test:
	php bin/phpunit

.PHONY: cc
cc:
	php bin/console cache:clear

.PHONY: install
install: vendor/autoload.php
	php bin/console doctrine:migrations:migrate -n
	php bin/console importmap:install
	php bin/console asset-map:compile
	php bin/console tailwind:build --minify
	composer dump-env prod
	php bin/console cache:clear

vendor/autoload.php: composer.lock composer.json
	composer install --no-dev --optimize-autoloader
	touch vendor/autoload.php