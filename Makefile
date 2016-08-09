all: cs dunit unit
travis: cs travis-unit
travis-benchmark: benchmark-travis
contrib: cs dunit unit

init:
	if [ ! -d vendor ]; then composer install; fi;

cs: init
	./vendor/bin/phpcs --standard=PSR2 src/

unit: init
	./vendor/bin/phpunit --coverage-text --coverage-html covHtml

benchmark: init
	./vendor/bin/phpbench run benchmarks/ --progress=dots --store --report='generator: "table", cols: ["benchmark", "subject", "index", "best", "mean", "mode", "worst", "diff"], break: ["benchmark"], sort: {mean: "asc"}'

benchmark-travis: init
	mkdir -p .phpbench_storage/xml
	mkdir -p .phpbench_storage/store
	mkdir -p .phpbench_storage/csv
	./vendor/bin/phpbench run benchmarks/ --progress=travis --store --dump-file=.phpbench_storage/xml/latest.xml --context=$(TRAVIS_BUILD_NUMBER) -vvv --report='generator: "table", cols: ["benchmark", "subject", "index", "best", "mean", "mode", "worst", "diff"], break: ["benchmark"], sort: {mean: "asc"}'
	if [ -f ".phpbench_storage/xml/previous.xml" ]; then ./vendor/bin/phpbench report --file=.phpbench_storage/xml/previous.xml --file=.phpbench_storage/xml/latest.xml --report='generator: "table", compare: "revs", cols: ["subject", "index", "mean"], compare_fields: ["best", "mean", "mode", "worst"]'; fi;
	mv .phpbench_storage/xml/latest.xml .phpbench_storage/xml/previous.xml

travis-benchmark-delimited: init
	if [ -f ".phpbench_storage/xml/previous.xml" ]; then ./vendor/bin/phpbench report --file=.phpbench_storage/xml/previous.xml --report='generator: "table", cols: ["benchmark", "subject", "index", "best", "mean", "mode", "worst", "diff"], break: ["benchmark"], sort: {mean: "asc"}' --output=delimited; fi;
	if [ -f ".phpbench_storage/xml/previous.xml" ]; then cat .phpbench_storage/csv/previous.csv; fi;

dunit: init
	./vendor/bin/dunit

travis-unit: init
	./vendor/bin/phpunit --coverage-text --coverage-clover ./build/logs/clover.xml

travis-coverage: init
	if [ -f ./build/logs/clover.xml ]; then wget https://scrutinizer-ci.com/ocular.phar && php ocular.phar code-coverage:upload --format=php-clover ./build/logs/clover.xml; fi
