<?php

use ShveiderDtoTest\DTO\Associative\Transfers\AssociativeTransfer;

$transfer = new AssociativeTransfer();

$expectAttributes = [
    'simple attribute' => '1',
    'attribute key' => '2',
    'attribute' => '3',
];
$transfer->setAttributes($expectAttributes);

assert(array_diff($expectAttributes, $transfer->toArray()['attributes']) == []);
assert($transfer->getAttribute('simple attribute') === '1');
assert($transfer->getAttribute('attribute') === '3');
assert($transfer->getAttribute('attribute key') === '2');
assert($transfer->hasAttribute('simple attribute'));
assert($transfer->hasAttribute('attribute'));
assert($transfer->hasAttribute('attribute key'));
assert($transfer->hasAttribute('not attribute key') === false);

$transfer2 = new AssociativeTransfer();
$expectTransfer2 = [
    'attributes' => [
        'key_1' => '123',
        'key_2' => '123',
        'key_3' => '123',
    ],
];

$transfer2->fromArray($expectTransfer2);
$actualTransfer2 = $transfer2->toArray();
assert(array_diff($expectTransfer2['attributes'], $actualTransfer2['attributes']) == []);

foreach ($expectTransfer2['attributes'] as $key => $attribute) {
    assert($transfer2->hasAttribute($key));
    assert($transfer2->hasAttribute($key . 'wrong_key') === false);
    assert($transfer2->getAttribute($key) === $attribute);
}

$transfer2->addAttribute('add_key', 'add attribute');
$transfer2->addAttribute('add_key_2', 'add attribute 2');
assert($transfer2->hasAttribute('add_key'));
assert($transfer2->hasAttribute('add_key_2'));
assert($transfer2->getAttribute('add_key') === 'add attribute');
assert($transfer2->getAttribute('add_key_2') === 'add attribute 2');

$expectTransfer2['attributes']['add_key'] = 'add attribute';
$expectTransfer2['attributes']['add_key_2'] = 'add attribute 2';

foreach ($expectTransfer2['attributes'] as $key => $attribute) {
    assert($transfer2->hasAttribute($key));
    assert($transfer2->getAttribute($key) === $attribute);
}
