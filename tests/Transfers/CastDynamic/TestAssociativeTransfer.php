<?php

namespace ShveiderDtoTest\Transfers\CastDynamic;

use ShveiderDto\AbstractCastDynamicTransfer;
use ShveiderDto\Attributes\ArrayOf;

class TestAssociativeTransfer extends AbstractCastDynamicTransfer
{
    protected array $__casts = [
        'singular' => ['attributes' => 'attribute'],
    ];

    protected array $attributes = [];
}
