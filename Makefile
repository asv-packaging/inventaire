server := "root@192.168.123.80"
domain := inventaire

.PHONY: install deploy

deploy:
	ssh -A $(server) 'cd /var/www/$(domain) && git pull origin developpement && make install'

install: vendor/autoload.php
	php bin/console doctrine:migrations:migrate -n
	@COMPOSER_ALLOW_SUPERUSER=true composer dump-env prod
	php bin/console cache:clear

vendor/autoload.php: composer.lock composer.json
	@COMPOSER_ALLOW_SUPERUSER=true composer install --no-dev --optimize-autoloader
	touch vendor/autoload.php