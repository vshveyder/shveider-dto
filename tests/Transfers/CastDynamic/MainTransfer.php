<?php

namespace ShveiderDtoTest\Transfers\CastDynamic;

use ShveiderDto\AbstractCastDynamicTransfer;
use ShveiderDto\Attributes\ValueWithConstruct;
use ShveiderDtoTest\VO\TestVo;

class MainTransfer extends AbstractCastDynamicTransfer
{
    protected array $__casts = [
        'transfers' => [
            'testAssociative' => TestAssociativeTransfer::class,
            'customer' => CustomerTransfer::class,
        ],
        'constructs' => [
            'testVo' => [TestVo::class, 'vString', 'vInt', 'vArray'],
        ],
    ];

    protected CustomerTransfer $customer;

    #[ValueWithConstruct]
    protected TestVo $testVo;

    protected TestAssociativeTransfer $testAssociative;
}
