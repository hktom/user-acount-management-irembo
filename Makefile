boot: ## Install and start the project
	cp .env.example .env
	docker-compose up -d --build 
	docker-compose exec app composer install
	docker-compose exec app php artisan migrate
	docker-compose exec app php artisan key:generate
	docker-compose exec app php artisan jwt:secret
	docker-compose exec app php artisan db:seed

up: ## Start the project
	docker-compose up -d

down: ## Stop the project
	docker-compose down