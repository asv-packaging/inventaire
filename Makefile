server := "root@192.168.123.80"
domain := inventaire

.PHONY: install deploy

deploy:
	ssh -A $(server) 'cd /var/www/$(domain) && git pull origin developpement && make install'

install: vendor/autoload.php
	export COMPOSER_ALLOW_SUPERUSER=1;
	php bin/console doctrine:migrations:migrate -n
	composer dump-env prod
	php bin/console cache:clear

vendor/autoload.php: composer.lock composer.json
	export COMPOSER_ALLOW_SUPERUSER=1;
	composer install --no-dev --optimize-autoloader
	touch vendor/autoload.php