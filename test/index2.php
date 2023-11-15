<?php

use ShveiderDtoTest\DTO\Module1\Transfers\Test2Transfer;
use ShveiderDtoTest\DTO\Module2\Transfers\TestTransfer;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new \ShveiderDto\Command\GenerateDtoTraitsCommand(
        new \ShveiderDto\ShveiderDtoFactory(),
        new \ShveiderDto\GenerateDTOConfig(
            __DIR__ . '/../test/DTO/*/Transfers',
            __DIR__ . '/../test/Generated',
            'ShveiderDtoTest\Generated',
            minified: true,
        ),
    ))->execute();
} catch (Exception $e) {
    echo $e->getMessage();

    die('execution filed');
}
