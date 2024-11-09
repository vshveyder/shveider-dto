<?php

use ShveiderDto\Command\GenerateDtoCacheFile;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\ShveiderDtoFactory;

require_once __DIR__ . '/../../vendor/autoload.php';

echo PHP_EOL . 'Generating cache.' . PHP_EOL;

(new GenerateDtoCacheFile(
    new ShveiderDtoFactory(),
    new GenerateDTOConfig(
        readFrom: __DIR__ . '/../Transfers/ProjectLevelAbstractCachedTransfer',
        writeTo: __DIR__ . '/../Cache/',
        writeToNamespace: 'ShveiderDtoTest\Cache',
    ),
))->execute();

echo 'Cache generated.' . PHP_EOL;
