server := "root@192.168.123.80"
site := "inventaire"

.PHONY: deploy

deploy:
	ssh -A $(server)$ 'cd /var/www/$(site) && git pull origin developpement && make install'

install:
	php bin/console doctrine:migrations:migrate -n
	composer dump-env prod
	php bin/console cache:clear

vendor/autoload.php: composer.lock composer.json
	composer install --no-dev --optimize-autoloader
	touch vendor/autoload.php