<?php

require_once __DIR__ . '/../vendor/autoload.php';

$regenerateTraits = __DIR__ . '/index-test-generate-traits.php';

`php $regenerateTraits`;

echo PHP_EOL . 'Transfer\'s test stated.' . PHP_EOL;

include 'cases/traits/test-default.php';
include 'cases/traits/test-from-array.php';
include 'cases/traits/test-add-methods.php';
include 'cases/traits/test-associative.php';
include 'cases/traits/test-to-array.php';
include 'cases/traits/test-value-with-construct.php';

echo 'Test Completed without any error.' . PHP_EOL;
