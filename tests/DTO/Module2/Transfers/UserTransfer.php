<?php

namespace ShveiderDtoTest\DTO\Module2\Transfers;

use ShveiderDto\AbstractTransfer;
use ShveiderDtoTest\DTO\Module2\Transfers\Generated\UserTransferTrait;

class UserTransfer extends AbstractTransfer
{
    use UserTransferTrait;

    protected ?string $name = null;

    protected ?int $age = null;

    protected ?AddressTransfer $address = null;
}
