# Default environment controller
COMPOSE_SCRIPT=./compose.sh

# Aliases
up:
	$(COMPOSE_SCRIPT) up --build

down:
	$(COMPOSE_SCRIPT) down

restart:
	$(COMPOSE_SCRIPT) down && $(COMPOSE_SCRIPT) up --build

logs:
	$(COMPOSE_SCRIPT) logs -f

ps:
	$(COMPOSE_SCRIPT) ps

exec-app:
	docker exec -it php_task_app bash

exec-db:
	docker exec -it php_task_db bash

prune:
	docker system prune -af --volumes

rebuild:
	docker-compose -f docker/docker-compose.base.yml build --no-cache

