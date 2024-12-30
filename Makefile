.PHONY: php-stan
php-stan: 
	vendor/bin/phpstan analyse src templates

.PHONY: php-cs
php-cs:
	php-cs-fixer fix
 
