<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Cast;

use ShveiderDto\AbstractCastTransfer;
use ShveiderDto\Attributes\ValueWithConstruct;
use ShveiderDtoTest\VO\TestVo;

class MainTransfer extends AbstractCastTransfer
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

    public CustomerTransfer $customer;

    #[ValueWithConstruct]
    public TestVo $testVo;

    public TestAssociativeTransfer $testAssociative;
}
