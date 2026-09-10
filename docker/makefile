# -------- Setup -------- #
SHELL = bash

DOCKER_EXEC = docker-compose exec
DOCKER_EXEC_AS_USER = $(DOCKER_EXEC) -u `id -u`
EXEC_PHP = $(DOCKER_EXEC_AS_USER) php
COMPOSER = $(EXEC_PHP) composer
SYMFONY = $(EXEC_PHP) php bin/console
ARGS = $(filter-out $@,$(MAKECMDGOALS))

## —— General —————————————————————————————————————————————————————————————————————————————————————
help: ## Shows available commands
	@echo "Available commands:"
	@echo ""
	@echo "Docker:"
	@echo "  make up              Start Docker containers"
	@echo "  make down            Stop Docker containers"
	@echo "  make build           Build Docker containers"
	@echo "  make shell           Open a shell in the PHP container"
	@echo "  make shell-sudo      Open a root shell in the PHP container"
	@echo "  make ps              Show Docker images"
	@echo "  make exec CMD=...    Run a command in the PHP container"
	@echo "  make exec-sudo CMD= Run a command as root"
	@echo ""
	@echo "Composer:"
	@echo "  make composer-install    Install Composer dependencies"
	@echo "  make composer-update     Update Composer dependencies"
	@echo "  make composer CMD=...    Run a Composer command"
	@echo ""
	@echo "Symfony:"
	@echo "  make symfony CMD=...     Run a Symfony console command"
	@echo ""
	@echo "SSL:"
	@echo "  make generate-ssl      Generate local SSL certificate"
	@echo ""



## —— SSL ————————————————————————————————————————————————————————————————————————————————————————
generate-ssl: ## Generates SSL certificate
	./generate-ssl.sh

## —— Docker ——————————————————————————————————————————————————————————————————————————————————————
up: ## Starts the Docker containers
	docker-compose up -d

down: ## Stops the Docker containers
	docker-compose down

build: ## Builds the Docker containers
	docker-compose build

shell: ## Opens a shell in the PHP container
	$(DOCKER_EXEC_AS_USER) php $(SHELL)

shell-sudo: ## Opens a root shell in the PHP container
	$(DOCKER_EXEC) php $(SHELL)

ps: ## Shows Docker images
	docker-compose images
	@echo "---------------------------------------------------------------------------------------------"

exec: ## Runs a command in the PHP container
	$(EXEC_PHP) $(ARGS)

exec-sudo: ## Runs a command in the PHP container as root
	$(DOCKER_EXEC) php $(ARGS)

## —— Composer ————————————————————————————————————————————————————————————————————————————————————
composer-install: ## Installs Composer dependencies
	$(COMPOSER) install

composer-update: ## Updates Composer dependencies
	$(COMPOSER) update

composer: ## Runs a Composer command
	$(COMPOSER) $(ARGS)

## —— Symfony —————————————————————————————————————————————————————————————————————————————————————
symfony: ## Runs a Symfony console command
	$(SYMFONY) $(ARGS)

## ————————————————————————————————————————————————————————————————————————————————————————————————
%:
	@:
