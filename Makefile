server := "root@192.168.123.80"
domain := "inventaire"

.PHONY: install deploy

deploy:
	ssh -A $(server)$ 'cd /var/www/$(domain) && git pull origin main && make install'

install: vendor/autoload.php
	php bin/console doctrine:migrations:migrate -n
	composer dump-env prod
	php bin/console cache:clear

vendor/autoload.php: composer.lock composer.json
	composer install --no-dev --optimize-autoloader
	touch vendor/autoload.php