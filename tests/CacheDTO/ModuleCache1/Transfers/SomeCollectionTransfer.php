<?php

namespace ShveiderDtoTest\CacheDTO\ModuleCache1\Transfers;

use ShveiderDto\Attributes\ArrayOf;
use ShveiderDto\AbstractCachedTransfer;

class SomeCollectionTransfer extends AbstractCachedTransfer
{
    #[ArrayOf(SomeResourceTransfer::class, 'resource')]
    public array $resources = [];

    #[ArrayOf('self')]
    public array $children = [];
}
