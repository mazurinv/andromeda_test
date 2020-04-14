start:
	docker-compose up -d
stop:
	docker-compose down
fix:
	docker-compose exec php chmod -R 777 /var/www