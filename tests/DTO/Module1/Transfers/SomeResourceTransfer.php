<?php

namespace ShveiderDtoTest\DTO\Module1\Transfers;

use ShveiderDto\AbstractTransfer;
use ShveiderDtoTest\DTO\Module1\Transfers\Generated\SomeResourceTransferTrait;

class SomeResourceTransfer extends AbstractTransfer
{
    use SomeResourceTransferTrait;
    protected int $id;
}
