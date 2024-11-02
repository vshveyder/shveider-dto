<?php

require_once __DIR__ . '/../../vendor/autoload.php';

echo PHP_EOL . 'Transfer\'s test stated.' . PHP_EOL;

$testCase = new \ShveiderDtoTest\TestCase(
    false,
    new \ShveiderDtoTest\Transfers\Configurable\MainTransfer(),
    \ShveiderDtoTest\Transfers\Configurable\AddressTransfer::class,
    \ShveiderDtoTest\Transfers\Configurable\CityTransfer::class,
);

$testCase->testFromArrayToArray();
$testCase->testValueObject();

echo 'Test Completed without any error.' . PHP_EOL;
