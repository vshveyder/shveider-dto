<?php

use ShveiderDto\Command\GenerateDtoCacheFile;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\ShveiderDtoFactory;

require_once __DIR__ . '/../../vendor/autoload.php';

echo PHP_EOL . 'Generating cache.' . PHP_EOL;
(new GenerateDtoCacheFile(
    new ShveiderDtoFactory(),
    new GenerateDTOConfig(
        readFrom: __DIR__ . '/../public/Transfers/Cached',
        writeTo:  __DIR__ . '/../src/Cache/',
        writeToNamespace: 'ShveiderDto\Cache',
    ),
))->execute();

echo 'Cache generated.' . PHP_EOL;

$testCase = new \ShveiderDtoTest\TestCase(
    false,
    new \ShveiderDtoTest\Transfers\Cached\MainTransfer(),
    \ShveiderDtoTest\Transfers\Cached\AddressTransfer::class,
    \ShveiderDtoTest\Transfers\Cached\CityTransfer::class,
);

$testCase->testFromArrayToArray();
$testCase->testValueObject();

echo 'Test Completed without any error.' . PHP_EOL;
