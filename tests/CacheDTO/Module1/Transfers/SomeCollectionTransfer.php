<?php

namespace ShveiderDtoTest\CacheDTO\Module1\Transfers;

use ShveiderDto\Attributes\ArrayOf;
use ShveiderDto\SVTransfer;

class SomeCollectionTransfer extends SVTransfer
{
    #[ArrayOf(SomeResourceTransfer::class, 'resource')]
    public array $resources = [];

    #[ArrayOf('self')]
    public array $children = [];
}
