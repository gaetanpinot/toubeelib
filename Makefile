phpdocker=toubeelib-api.toubeelib-1
install: 
	sudo docker compose up -d
	 sudo docker exec -it $(phpdocker) composer install
genereDb:
	sudo docker exec -it $(phpdocker) php ./src/infrastructure/genereDB.php
