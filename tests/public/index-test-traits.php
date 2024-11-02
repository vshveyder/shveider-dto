<?php

require_once __DIR__ . '/../../vendor/autoload.php';

echo PHP_EOL . 'Transfer\'s test stated.' . PHP_EOL;

$testCase = new \ShveiderDtoTest\TestCase(
    true,
    new \ShveiderDtoTest\Transfers\Traits\MainTransfer(),
    \ShveiderDtoTest\Transfers\Traits\AddressTransfer::class,
    \ShveiderDtoTest\Transfers\Traits\CityTransfer::class,
);

$testCase->testFromArrayToArray();
$testCase->testValueObject();
$testCase->testAssociativeMethods();
$testCase->testAddMethods();
$testCase->testModifiedToArray();

echo 'Test Completed without any error.' . PHP_EOL;
