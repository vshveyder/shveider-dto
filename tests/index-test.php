<?php

require_once __DIR__ . '/../vendor/autoload.php';

echo PHP_EOL . 'Transfer\'s test stated.' . PHP_EOL;

include 'cases/test-default.php';
include 'cases/test-from-array.php';
include 'cases/test-add-methods.php';
include 'cases/test-associative.php';
include 'cases/test-to-array.php';

echo 'Test Completed without any error.' . PHP_EOL;
