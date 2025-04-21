# Имя проекта
PROJECT_NAME := "lims-ulab"

# Папки с кодом и тестами
SRC_DIR := application
TEST_DIR := tests

# Список PHP-файлов
PHP_FILES := $(shell find . -name '*.php' -not -path './vendor/*')

# Переменные для тестов и качества кода
PHPUNIT := vendor/bin/phpunit
PHPBENCH := vendor/bin/phpbench
PHPCS := ~/.composer/vendor/bin/phpcs
PHPCSFIXER := ~/.composer/vendor/bin/php-cs-fixer
PSALM := vendor/bin/psalm
PHPMD := vendor/bin/phpmd
COVERAGE_OUT := coverage.out
COVERAGE_XML := coverage.xml
REPORT_XML := report.xml
PHPCS_REPORT := phpcs-report.xml
PHPCSFIXER_REPORT := php-cs-fixer-report.xml
PSALM_REPORT := psalm-report.json
PHPMD_REPORT := phpmd-report.xml

.PHONY: all dep build clean test benchmark e2e test-ci benchmark-ci e2e-ci coverage coverage-ci lint lint-ci format-ci static-analysis-ci

all: build

lint: ## Lint the files
	@$(PHPCS) --standard=PSR12 $(SRC_DIR)

lint-ci: ## Lint the files in CI
	@composer global require squizlabs/php_codesniffer
	@$(PHPCS) --standard=PSR12 --report=junit --report-file=$(PHPCS_REPORT) $(SRC_DIR)

format-ci: ## Check code formatting in CI
	@composer global require friendsofphp/php-cs-fixer
	@$(PHPCSFIXER) fix $(SRC_DIR) --rules=@PSR12 --dry-run --format=junit > $(PHPCSFIXER_REPORT)

static-analysis-ci: dep ## Run static analysis in CI
	@$(PSALM) --report=$(PSALM_REPORT) --output-format=gitlab
	@$(PHPMD) $(SRC_DIR) xml codesize,unusedcode,naming --reportfile=$(PHPMD_REPORT)

test: dep ## Run unit tests
	@$(PHPUNIT) --configuration phpunit.xml --log-junit $(REPORT_XML) --coverage-text --coverage-clover $(COVERAGE_OUT)

benchmark: dep ## Run benchmarks
	@$(PHPBENCH) run $(TEST_DIR)/Benchmark --report=aggregate --output=junit --output-file=$(REPORT_XML)

e2e: dep ## Run integration (e2e) tests
	@$(PHPUNIT) --configuration phpunit.xml --group integration --log-junit $(REPORT_XML) --coverage-text --coverage-clover $(COVERAGE_OUT)

test-ci: dep ## Run unit tests in CI
	@$(PHPUNIT) --configuration phpunit.xml --log-junit $(REPORT_XML) --coverage-clover $(COVERAGE_OUT)

benchmark-ci: dep ## Run benchmarks in CI
	@$(PHPBENCH) run $(TEST_DIR)/Benchmark --report=aggregate --output=junit --output-file=$(REPORT_XML)

e2e-ci: dep ## Run integration (e2e) tests in CI
	@$(PHPUNIT) --configuration phpunit.xml --group integration --log-junit $(REPORT_XML) --coverage-clover $(COVERAGE_OUT)

coverage: ## Generate coverage report
	@cat $(COVERAGE_OUT)

coverage-ci: ## Generate coverage report in CI
	@cp $(COVERAGE_OUT) $(COVERAGE_XML)

infra: ## Start the infrastructure
	@docker compose up -d --build

dep: ## Install dependencies
	@composer install --no-progress --prefer-dist --optimize-autoloader

build: dep ## Build the project
	@echo "No binary build required for PHP"

clean: ## Remove generated files
	@rm -f $(REPORT_XML) $(COVERAGE_OUT) $(COVERAGE_XML) $(PHPCS_REPORT) $(PHPCSFIXER_REPORT) $(PSALM_REPORT) $(PHPMD_REPORT)

help: ## Display this help screen
	@grep -h -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'