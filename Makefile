.PHONY: up start stop down log artisan migrate seed composer tests failing-tests fix-permissions ide-helper

# Set dir of Makefile to a variable to use later
MAKEPATH := $(abspath $(lastword $(MAKEFILE_LIST)))
PWD := $(dir $(MAKEPATH))

up:
	cd laradock \
	&& docker-compose up -d  nginx mysql \
	&& cd ../

start:
	cd laradock && \
	docker-compose start

stop:
	cd laradock && \
	docker-compose stop

down:
	cd laradock && \
	docker-compose down

cmd=""

#SSH into workspace
ws:
	cd laradock && \
	docker-compose exec \
		workspace \
		bash

#execute npm into VM
npm:
	cd laradock && \
	docker exec -it \
		prof_workspace \
		npm $(cmd)

#Run artisan command into VM
artisan:
	docker exec -it \
		prof_fpm \
		php artisan $(cmd) \
		2>/dev/null || true

#Run migrat command into VM
migrate:
	docker exec -it \
		prof_fpm \
		php artisan migrate --step \
		2>/dev/null || true

migrate-rollback:
	docker exec -it \
		prof_fpm \
		php artisan migrate:rollback \
		2>/dev/null || true

seed:
	docker exec -it \
		prof_fpm \
		php artisan db:seed \
		2>/dev/null || true

cmd=""
composer:
	docker exec -it \
		prof_fpm \
		composer $(cmd) \
		2>/dev/null || true

composer-dump-autoload:
	docker exec -it \
		prof_fpm \
		composer dump-autoload -o \
		2>/dev/null || true

tests:
	docker exec -it \
		prof_fpm \
		php ./vendor/bin/phpunit \
		2>/dev/null || true

failing-tests:
	docker exec -it \
		prof_fpm \
		php ./vendor/bin/phpunit --group=failing \
		2>/dev/null || true

fix-permissions:
	docker exec -it prof_fpm chown -R 1000:100 ./app 2>/dev/null || true && \
	docker exec -it prof_fpm chown -R 1000:100 ./vendor 2>/dev/null || true && \
	docker exec -it prof_fpm chown -R 1000:100 ./database 2>/dev/null || true && \
	docker exec -it prof_fpm chown -R 1000:100 ./bootstrap 2>/dev/null || true && \
	docker exec -it prof_fpm chown -R 1000:100 ./storage/app 2>/dev/null || true && \
	docker exec -it prof_fpm chown -R 1000:100 ./storage/logs 2>/dev/null || true && \
	docker exec -it prof_fpm chown -R 1000:100 ./resources 2>/dev/null || true && \
	docker exec -it prof_fpm chown    1000:100 ./composer.lock 2>/dev/null || true

ide-helper:
	docker exec -it prof_fpm php artisan ide-helper:generate 2>/dev/null || true && \
	docker exec -it prof_fpm php artisan ide-helper:models --nowrite 2>/dev/null || true && \
	docker exec -it prof_fpm chown 1000:100 ./_ide_helper.php 2>/dev/null || true && \
	docker exec -it prof_fpm chown 1000:100 ./_ide_helper_models.php 2>/dev/null || true
