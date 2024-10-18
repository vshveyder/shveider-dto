<?php

use ShveiderDto\Command\GenerateDtoCacheFile;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\ShveiderDtoFactory;

require_once __DIR__ . '/../vendor/autoload.php';

echo PHP_EOL . 'Generating cache.' . PHP_EOL;
(new GenerateDtoCacheFile(
    new ShveiderDtoFactory(),
    new GenerateDTOConfig(
        readFrom: __DIR__ . '/../tests/CacheDTO/*/Transfers',
        writeTo:  __DIR__ . '/../src/Cache/',
        writeToNamespace: 'ShveiderDto\Cache',
    ),
))->execute();

include 'cases/cache/test-from-and-to-array.php';
include 'cases/cache/test-value-with-construct.php';

echo 'Test Completed without any error.' . PHP_EOL;
