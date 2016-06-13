test: 
	bin/phpspec run -fpretty
	bin/symfony-integration-checker check -vvv
	bin/php-cs-fixer --diff --dry-run -v fix

fix:
	bin/php-cs-fixer fix -vvv
