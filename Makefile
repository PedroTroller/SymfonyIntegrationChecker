test: 
	bin/symfony-integration-checker check -vvv
	vendor/bin/php-cs-fixer --diff --dry-run -v fix

fix:
	vendor/bin/php-cs-fixer fix -vvv
