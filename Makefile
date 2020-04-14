start:
	docker-compose up -d
stop:
	docker-compose down
bash:
	docker-compose exec php bash
fix:
	docker-compose exec php chmod -R 777 /var/www