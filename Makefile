server := "root@192.168.123.80"
domain := inventaire
branch := developpement

.PHONY: install deploy

deploy:
	ssh -A $(server) 'cd /var/www/$(domain) && git checkout . && git pull origin $(branch) && make install'

install: vendor/autoload.php
	php bin/console doctrine:migrations:migrate -n
	@COMPOSER_ALLOW_SUPERUSER=true composer dump-env prod
	php bin/console cache:clear
	chmod -R 777 /var/www/$(domain)/

vendor/autoload.php: composer.lock composer.json
	@COMPOSER_ALLOW_SUPERUSER=true composer install --no-dev --optimize-autoloader
	touch vendor/autoload.php
	chmod -R 777 /var/www/$(domain)/