<?php

namespace ShveiderDtoTest\DTO\Module1\Transfers;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\Attributes\ArrayOf;
use ShveiderDtoTest\DTO\Module1\Transfers\Generated\SomeCollectionTransferTrait;

class SomeCollectionTransfer extends AbstractConfigurableTransfer
{
    use SomeCollectionTransferTrait;
    #[ArrayOf(SomeResourceTransfer::class, 'resource')]
    protected array $resources = [];

    #[ArrayOf('self')]
    protected array $children = [];
}
