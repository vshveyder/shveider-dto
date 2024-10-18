<?php

namespace ShveiderDtoTest\CacheDTO\ModuleCache1\Transfers;

use ShveiderDto\AbstractCachedTransfer;
use ShveiderDto\Attributes\ValueWithConstruct;
use ShveiderDtoTest\CacheDTO\ModuleCache1\ValueObjectWithConstruct;

class SomeResourceTransfer extends AbstractCachedTransfer
{
    public int $id;

    #[ValueWithConstruct]
    protected TransferWithConstruct $transferWithConstruct;

    #[ValueWithConstruct]
    protected ValueObjectWithConstruct $objectWithConstruct;

    /**
     * @return \ShveiderDtoTest\CacheDTO\ModuleCache1\Transfers\TransferWithConstruct
     */
    public function getTransferWithConstruct(): TransferWithConstruct
    {
        return $this->transferWithConstruct;
    }

    /**
     * @return \ShveiderDtoTest\CacheDTO\ModuleCache1\ValueObjectWithConstruct
     */
    public function getObjectWithConstruct(): ValueObjectWithConstruct
    {
        return $this->objectWithConstruct;
    }
}
