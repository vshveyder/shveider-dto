<?php

use ShveiderDtoTest\DTO\Module1\Transfers\SomeCollectionTransfer;
use ShveiderDtoTest\DTO\Module1\Transfers\SomeResourceTransfer;

$collection = (new SomeCollectionTransfer())
    ->fromArray([
        'resources' => [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3]
        ],
        'children' => [
            [
                'resources' => [
                    ['id' => 1],
                    ['id' => 2],
                    ['id' => 3]
                ],
                'children' => [
                    [
                        'resources' => [
                            ['id' => 11],
                            ['id' => 22],
                            ['id' => 33]
                        ]
                    ],
                    [
                        'resources' => [
                            ['id' => 44],
                            ['id' => 55],
                            ['id' => 66]
                        ],
                    ]
                ]
            ],
            [
                'resources' => [
                    ['id' => 4],
                    ['id' => 5],
                    ['id' => 6]
                ],
                'children' => [
                    [
                        'resources' => [
                            ['id' => 111],
                            ['id' => 222],
                            ['id' => 333]
                        ]
                    ],
                    [
                        'resources' => [
                            ['id' => 444],
                            ['id' => 545],
                            ['id' => 665]
                        ],
                    ]
                ]
            ]
        ]
    ]);

$toArray = $collection->toArray();
assert(is_a($toArray['children'][0], SomeCollectionTransfer::class));
assert(is_a($toArray['resources'][0], SomeResourceTransfer::class));
assert(is_a($toArray['children'][0]->toArray()['children'][0], SomeCollectionTransfer::class));

$toArrayRecursive = $collection->toArray(true);
assert(is_array($toArrayRecursive['children'][0]));
assert(is_array($toArrayRecursive['children'][0]['children'][0]));
assert($toArrayRecursive['children'][0]['children'][0]['resources'][0]['id'] === 11);
assert($toArrayRecursive['children'][0]['children'][0]['resources'][1]['id'] === 22);
assert($toArrayRecursive['children'][0]['children'][0]['resources'][2]['id'] === 33);
