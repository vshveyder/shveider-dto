<?php

namespace ShveiderDtoTest\DTO\Associative\Transfers;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\Attributes\ArrayOf;
use ShveiderDtoTest\DTO\Associative\Transfers\Generated\AssociativeTransferTrait;

class AssociativeTransfer extends AbstractConfigurableTransfer
{
    use AssociativeTransferTrait;

    #[ArrayOf('string', 'attribute', true)]
    protected array $attributes;
}
