build:
	docker-compose build api

start:
	docker-compose up -d api

stop:
	docker-compose down

logs:
	docker-compose logs -f api