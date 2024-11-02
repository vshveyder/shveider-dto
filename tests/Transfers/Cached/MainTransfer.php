<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Cached;

use ShveiderDto\Attributes\ValueWithConstruct;
use ShveiderDtoTest\VO\TestVo;

class MainTransfer extends ProjectLevelAbstractCachedTransfer
{
    public CustomerTransfer $customer;

    #[ValueWithConstruct]
    public TestVo $testVo;

    public TestAssociativeTransfer $testAssociative;
}
