<?php

use ShveiderDto\AbstractTransfer;

function testValueWithConstruct(AbstractTransfer $transfer): void
{
    $transfer->fromArray([
        'objectWithConstruct' => [
            'stringValue' => 'some string',
            'intValue' => 1111,
        ],
        'transferWithConstruct' => [
            'stringValue' => 'some string',
            'intValue' => 1111,
            'someArray' => [1,2,3,44,98],
            'strangeField' => 'strange text',
        ],
    ]);

    assert($transfer->getObjectWithConstruct()->getStringValue() === 'some string');
    assert($transfer->getObjectWithConstruct()->getIntValue() === 1111);

    assert($transfer->getTransferWithConstruct()->getStringValue() === 'some string');
    assert($transfer->getTransferWithConstruct()->getIntValue() === 1111);
    assert($transfer->getTransferWithConstruct()->getStrangeField() === 'strange text');
    assert(array_sum($transfer->getTransferWithConstruct()->getSomeArray()) === array_sum([1,2,3,44,98]));
}
