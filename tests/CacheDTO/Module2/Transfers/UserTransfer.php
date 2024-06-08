<?php

namespace ShveiderDtoTest\CacheDTO\Module2\Transfers;

use ShveiderDto\SVTransfer;

class UserTransfer extends SVTransfer
{
    public ?string $name = null;

    public ?int $age = null;

    public ?AddressTransfer $address = null;
}
