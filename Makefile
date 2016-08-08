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
	./vendor/bin/phpbench run benchmarks/ --report=aggregate --progress=dots --store

benchmark-travis: init
	mkdir -p .phpbench_storage/xml
	mkdir -p .phpbench_storage/store
	./vendor/bin/phpbench run benchmarks/ --report=aggregate --progress=travis --store --dump-file=.phpbench_storage/xml/latest.xml --context=$(TRAVIS_BUILD_NUMBER)
	if [ -f ".phpbench_storage/xml/previous.xml" ]; then ./vendor/bin/phpbench report --file=.phpbench_storage/xml/previous.xml --file=.phpbench_storage/xml/latest.xml --report=compare; fi;
	mv .phpbench_storage/xml/latest.xml .phpbench_storage/xml/previous.xml

dunit: init
	./vendor/bin/dunit

travis-unit: init
	./vendor/bin/phpunit --coverage-text --coverage-clover ./build/logs/clover.xml

travis-coverage: init
	if [ -f ./build/logs/clover.xml ]; then wget https://scrutinizer-ci.com/ocular.phar && php ocular.phar code-coverage:upload --format=php-clover ./build/logs/clover.xml; fi
