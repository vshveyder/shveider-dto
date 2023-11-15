<?php

namespace ShveiderDtoTest\DTO\Module2\Transfers;

use ShveiderDto\AbstractTransfer;

class UserTransfer extends AbstractTransfer
{
    protected ?string $name = null;

    protected ?int $age = null;

    protected ?AddressTransfer $address = null;
}
