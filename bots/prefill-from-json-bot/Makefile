SHELL ?= /bin/bash
ARGS = $(filter-out $@,$(MAKECMDGOALS))

.SILENT: ;               # no need for @
.ONESHELL: ;             # recipes execute in same shell
.NOTPARALLEL: ;          # wait for this target to finish
.EXPORT_ALL_VARIABLES: ; # send all vars to shell
Makefile: ;              # skip prerequisite discovery

# Run make help by default
.DEFAULT_GOAL = help

.PHONY: prepare
prepare: ## Initial project setup
	cp .env.example .env; php artisan key:generate --ansi

.PHONY: build
build: ## Build image
	docker build \
		-t "prefill-from-json-bot/app" \
		docker/8.0

.PHONY: up
up: ## Up containers
	docker-compose up -d

.PHONY: down
down: ## Stop and kill containers
	docker-compose down

.PHONY: bash
bash: ## Open bash inside the app container
	docker-compose exec app bash

.PHONY: cs
cs: ## Check code style
	docker-compose exec app bash -c "vendor/bin/pint --test \
		&& vendor/bin/phpstan analyse -c phpstan.neon \
 		&& vendor/bin/phpmd ./app text codesize"

.PHONY: test
test: ## Run tests inside the app container
	docker-compose exec app bash -c "vendor/bin/phpunit"

.PHONY: help
help: ## Show this help and exit (default target)
	echo ''
	printf "                     %s: \033[94m%s\033[0m \033[90m[%s] [%s]\033[0m\n" "Usage" "make" "target" "ENV_VARIABLE=ENV_VALUE ..."
	echo ''
	echo '                     Available targets:'
	# Print all commands, which have '##' comments right of it's name.
	# Commands gives from all Makefiles included in project.
	# Sorted in alphabetical order.
	echo '                     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━'
	grep -hE '^[a-zA-Z. 0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | \
		 awk 'BEGIN {FS = ":.*?## " }; {printf "\033[36m%+20s\033[0m: %s\n", $$1, $$2}'
	echo '                     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━'
	echo ''

%:
	@:
