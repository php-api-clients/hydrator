travis-benchmark: benchmark-travis

all:
	composer run-script qa-all --timeout=0

all-coverage:
	composer run-script qa-all-coverage --timeout=0

ci:
	composer run-script qa-ci --timeout=0

ci-extended:
	composer run-script qa-ci-extended --timeout=0

contrib:
	composer run-script qa-contrib --timeout=0

init:
	composer ensure-installed

cs:
	composer cs

cs-fix:
	composer cs-fix

unit:
	composer run-script unit --timeout=0

unit-coverage:
	composer run-script unit-coverage --timeout=0

ci-coverage: init
	composer ci-coverage

benchmark:
	./vendor/bin/phpbench run benchmarks/ --progress=dots --store --report='generator: "table", cols: ["benchmark", "subject", "params", "best", "mean", "mode", "worst", "diff"], break: ["benchmark"], sort: {mean: "asc"}'

travis-benchmark:
	mkdir -p .phpbench_storage/xml
	mkdir -p .phpbench_storage/store
	mkdir -p .phpbench_storage/csv
	./vendor/bin/phpbench run benchmarks/ --progress=travis --store --dump-file=.phpbench_storage/xml/latest.xml --context=$(TRAVIS_BUILD_NUMBER) -vvv --report='generator: "table", cols: ["benchmark", "subject", "params", "best", "mean", "mode", "worst", "diff"], break: ["benchmark"], sort: {mean: "asc"}'
	if [ -f ".phpbench_storage/xml/previous.xml" ]; then ./vendor/bin/phpbench report --file=.phpbench_storage/xml/previous.xml --file=.phpbench_storage/xml/latest.xml --report='generator: "table", compare: "revs", cols: ["subject", "params", "mean"], compare_fields: ["best", "mean", "mode", "worst"]'; fi;
	mv .phpbench_storage/xml/latest.xml .phpbench_storage/xml/previous.xml

travis-benchmark-delimited:
	if [ -f ".phpbench_storage/xml/previous.xml" ]; then ./vendor/bin/phpbench report --file=.phpbench_storage/xml/previous.xml --report='generator: "table", cols: ["benchmark", "subject", "params", "best", "mean", "mode", "worst", "diff"], break: ["benchmark"], sort: {mean: "asc"}' --output=delimited; fi;
	if [ -f ".phpbench_storage/xml/previous.xml" ]; then cat .phpbench_storage/csv/previous.csv; fi;
