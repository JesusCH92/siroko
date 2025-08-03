#! /bin/bash

##	create-network:					crete network docker
create-network:
	docker network create app-network | true

##	deploy:							deploying app
deploy:
	-docker network create app-network | true
	-docker-compose -p app up -d
	-@docker exec -it php-fpm composer install
	-@docker exec -it php-fpm sh -c "until php bin/console doctrine:query:sql 'SELECT 1'; do echo '⏳ Waiting for DB...'; sleep 2; done"
	-@docker exec -it php-fpm php bin/console doctrine:migrations:migrate --no-interaction
	-@docker exec -it php-fpm php bin/console lexik:jwt:generate-keypair
	-@docker exec -it php-fpm php bin/phpunit --testdox
