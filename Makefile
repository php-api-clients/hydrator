all: cs dunit unit
travis: cs travis-unit benchmark-travis benchmark-previous
contrib: cs dunit unit

init:
	if [ ! -d vendor ]; then composer install; fi;

cs: init
	./vendor/bin/phpcs --standard=PSR2 src/

unit: init
	./vendor/bin/phpunit --coverage-text --coverage-html covHtml

benchmark: init
	./vendor/bin/phpbench run benchmarks/ --report=aggregate --progress=dots --store

benchmark-previous: init
	./vendor/bin/phpbench show latest-1

benchmark-travis: init
	./vendor/bin/phpbench run benchmarks/ --report=aggregate --progress=travis

dunit: init
	./vendor/bin/dunit

travis-unit: init
	./vendor/bin/phpunit --coverage-text --coverage-clover ./build/logs/clover.xml

travis-coverage: init
	if [ -f ./build/logs/clover.xml ]; then wget https://scrutinizer-ci.com/ocular.phar && php ocular.phar code-coverage:upload --format=php-clover ./build/logs/clover.xml; fi
