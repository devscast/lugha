.DEFAULT_GOAL := help
php := php
composer := composer

# -----------------------------------
# Recipes
# -----------------------------------
.PHONY: help
help: ## affiche cet aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: lint
lint: vendor/autoload.php ## affiche les erreurs de formatage de code
	$(php) vendor/bin/ecs
	$(php) vendor/bin/phpstan
	$(php) vendor/bin/rector --dry-run

.PHONY: test
test: vendor/autoload.php ## lance les tests
	$(php) vendor/bin/phpunit

.PHONY: lint-fix
lint-fix: vendor/autoload.php ## corrige les erreurs de formatage de code
	$(php) vendor/bin/ecs --fix
	$(php) vendor/bin/rector

vendor/autoload.php: composer.lock # installe les dépendances PHP
	$(composer) install
	$(composer) dump-autoload
