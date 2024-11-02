<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Configurable;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\Attributes\ValueWithConstruct;
use ShveiderDtoTest\VO\TestVo;

class MainTransfer extends AbstractConfigurableTransfer
{
    protected array $__registered_transfers = [
        'customer' => CustomerTransfer::class,
        'testAssociative' => TestAssociativeTransfer::class,
    ];

    protected array $__registered_values_with_construct = [
        'testVo' => [TestVo::class, 'vString', 'vInt', 'vArray'],
    ];

    public CustomerTransfer $customer;

    #[ValueWithConstruct]
    public TestVo $testVo;

    public TestAssociativeTransfer $testAssociative;
}
