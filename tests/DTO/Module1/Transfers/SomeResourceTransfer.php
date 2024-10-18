<?php

namespace ShveiderDtoTest\DTO\Module1\Transfers;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\Attributes\ValueWithConstruct;
use ShveiderDtoTest\CacheDTO\ModuleCache1\Transfers\TransferWithConstruct;
use ShveiderDtoTest\CacheDTO\ModuleCache1\ValueObjectWithConstruct;
use ShveiderDtoTest\DTO\Module1\Transfers\Generated\SomeResourceTransferTrait;

class SomeResourceTransfer extends AbstractConfigurableTransfer
{
    use SomeResourceTransferTrait;
    protected int $id;

    #[ValueWithConstruct]
    protected TransferWithConstruct $transferWithConstruct;

    #[ValueWithConstruct]
    protected ValueObjectWithConstruct $objectWithConstruct;
}
