<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$testCase = new \ShveiderDtoTest\TestCase(
    false,
    new \ShveiderDtoTest\Transfers\Cast\MainTransfer(),
    \ShveiderDtoTest\Transfers\Cast\AddressTransfer::class,
    \ShveiderDtoTest\Transfers\Cast\CityTransfer::class,
);

$testCase->testFromArrayToArray();
$testCase->testValueObject();

echo 'Test Completed without any error.' . PHP_EOL;