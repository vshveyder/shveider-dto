<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Traits;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\Attributes\ValueWithConstruct;
use ShveiderDtoTest\Transfers\Traits\Generated\MainTransferTrait;
use ShveiderDtoTest\VO\TestVo;

class MainTransfer extends AbstractConfigurableTransfer
{
    use MainTransferTrait;

    protected CustomerTransfer $customer;

    #[ValueWithConstruct]
    protected TestVo $testVo;

    protected TestAssociativeTransfer $testAssociative;
}
