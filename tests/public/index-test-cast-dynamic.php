<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$testCase = new \ShveiderDtoTest\TestCase(
    true,
    new \ShveiderDtoTest\Transfers\CastDynamic\MainTransfer(),
    \ShveiderDtoTest\Transfers\CastDynamic\AddressTransfer::class,
    \ShveiderDtoTest\Transfers\CastDynamic\CityTransfer::class,
);

$testCase->testFromArrayToArray();
$testCase->testValueObject();
$testCase->testAssociativeMethods();
$testCase->testAddMethods();
$testCase->testModifiedToArray();

echo 'Test Completed without any error.' . PHP_EOL;
