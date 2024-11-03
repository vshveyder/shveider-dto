<?php

require_once __DIR__ . '/../../vendor/autoload.php';

echo PHP_EOL . 'Generating php doc.' . PHP_EOL;

(new \ShveiderDto\Command\GeneratePhpDocStubCommand(
    new \ShveiderDto\ShveiderDtoFactory(),
    new \ShveiderDto\GenerateDTOConfig(__DIR__ . '/../Transfers/CastDynamic'),
))->execute();
echo PHP_EOL . 'Finished' . PHP_EOL;
