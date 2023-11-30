<?php

namespace ShveiderDtoTest\DTO\Associative\Transfers;

use ShveiderDto\AbstractTransfer;
use ShveiderDto\Attributes\ArrayOf;
use ShveiderDtoTest\DTO\Associative\Transfers\Generated\AssociativeTransferTrait;

class AssociativeTransfer extends AbstractTransfer
{
    use AssociativeTransferTrait;

    #[ArrayOf('string', 'attribute', true)]
    protected array $attributes;
}
