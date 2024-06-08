<?php

require_once __DIR__ . '/../vendor/autoload.php';

echo PHP_EOL . 'Generating cache.' . PHP_EOL;
(new \ShveiderDto\Command\GenerateDtoCacheFile(
    new \ShveiderDto\ShveiderDtoFactory(),
    new \ShveiderDto\GenerateDTOConfig(
        readFrom: __DIR__ . '/../tests/CacheDTO/*/Transfers',
        writeTo:  __DIR__ . '/../src/Cache/TransferCache.php',
        writeToNamespace: '\ShveiderDto\Cache\TransferCache',
    ),
))->execute();

include 'cases/cache/test-from-and-to-array.php';

echo 'Test Completed without any error.' . PHP_EOL;
