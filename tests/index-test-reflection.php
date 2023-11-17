<?php

require_once __DIR__ . '/../vendor/autoload.php';

$reflectionTransfer = new \ShveiderDtoTest\DTO\Module4\MyReflectionTransfer();
$reflectionTransfer->fromArray([
    'name' => 'SomeName',
    'firstName' => 'testFirstname',
    'dateTime' => new DateTime(),
    'fullDate' => null,
    'city' => 'Odessa',
    'country' => 'Ukraine',
    'zip' => '33061',
    'street' => 'Test Street',
    'streetNumber' => 123,
]);

var_dump($reflectionTransfer->toArray());