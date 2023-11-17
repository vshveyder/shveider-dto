<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ShveiderDto\Command\GenerateDtoTraitsCommand;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\ShveiderDtoFactory;

(new GenerateDtoTraitsCommand(
    new ShveiderDtoFactory(),
    new GenerateDTOConfig(
        __DIR__ . '/../tests/DTO/*/Transfers',
        minified: false,
    ),
))->execute();

(new \ShveiderDto\Command\GenerateDtoTraitsCommand(
    new \ShveiderDto\ShveiderDtoFactory(),
    new \ShveiderDto\GenerateDTOConfig(
        __DIR__ . '/../tests/DTO/*/Transfers',
        __DIR__ . '/../tests/Generated',
        'ShveiderDtoTest\Generated',
        minified: false,
    ),
))->execute();
