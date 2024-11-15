phpdocker=api_toubeelib
install: 
	make up
	make composer
	make genereDb
up:
	docker compose up -d --remove-orphans --build
composer:
	docker exec -it $(phpdocker) composer install
genereDb:
	docker exec -it $(phpdocker) php ./src/infrastructure/genereAuthDb.php
	docker exec -it $(phpdocker) php ./src/infrastructure/genereDB.php
watchLogs:
	watch -n 2 tail app/var/logs
