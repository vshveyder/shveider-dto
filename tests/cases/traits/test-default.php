<?php

use ShveiderDtoTest\DTO\Module1\Transfers\Test2Transfer;
use ShveiderDtoTest\DTO\Module2\Transfers\AddressTransfer;
use ShveiderDtoTest\DTO\Module2\Transfers\TestTransfer;

$date1 = new DateTime();
$test2Transfer = new Test2Transfer();
$test2Transfer->setName('test 2 name');
$test2Transfer->setFullDate(null);
$test2Transfer->setDateTime($date1);

assert($test2Transfer->getName() === 'test 2 name', 'Name are equal');
assert($test2Transfer->getFullDate() === null, 'Full date are equal');
assert($test2Transfer->getDateTime()->getTimestamp() === $date1->getTimestamp(), 'dateTime are equal');

$expectedArray = ['name' => 'test 2 name', 'firstName' => null, 'fullDate' => null, 'dateTime' => $date1];
$array = $test2Transfer->toArray();
foreach ($expectedArray as $name => $item) {
    assert(array_key_exists($name, $array), $name . ' exists in toArray');
    assert($array[$name] == $item, $name . '  is correct');
}

assert(array_diff_key(
    $test2Transfer->modifiedToArray(),
    ['name' => 'test 2 name', 'fullDate' => null, 'dateTime' => $date1],
) == []);

assert(array_diff_key(
    ['name' => 'test 2 name', 'fullDate' => null, 'dateTime' => $date1],
    $test2Transfer->modifiedToArray(),
) == []);

$test1Transfer = new TestTransfer();
assert($test1Transfer->toArray() == []);
assert($test1Transfer->modifiedToArray() == []);
assert(array_diff($test1Transfer->modifiedToArray(), []) == []);

$addressTransfer = new AddressTransfer();
assert(array_diff(
    ['city' => null, 'country' => null, 'zip' => null, 'street' => null, 'streetNumber' => null],
    $addressTransfer->toArray(),
) == []);
$addressTransfer->fromArray([
    'city' => 'Odessa',
    'country' => 'Ukraine',
]);

assert(array_diff([
    'city' => 'Odessa',
    'country' => 'Ukraine',
], $addressTransfer->modifiedToArray()) == []);
assert(array_diff($addressTransfer->modifiedToArray(), [
    'city' => 'Odessa',
    'country' => 'Ukraine',
]) == []);
assert(array_diff(['city' => 'Odessa', 'country' => 'Ukraine', 'zip' => null, 'street' => null, 'streetNumber' => null], $addressTransfer->toArray()) == []);

$addressTransfer2 = new AddressTransfer();
$addressTransfer2->fromArray($addressTransfer->modifiedToArray());
assert(array_diff(['city' => 'Odessa', 'country' => 'Ukraine', 'zip' => null, 'street' => null, 'streetNumber' => null], $addressTransfer2->toArray()) == []);
assert(array_diff($addressTransfer2->modifiedToArray(), [
        'city' => 'Odessa',
        'country' => 'Ukraine',
]) == []);

$addressTransfer3 = new AddressTransfer();
$addressTransfer3->fromArray($addressTransfer->toArray());
assert(array_diff(['city' => 'Odessa', 'country' => 'Ukraine', 'zip' => null, 'street' => null, 'streetNumber' => null], $addressTransfer3->modifiedToArray()) == []);

