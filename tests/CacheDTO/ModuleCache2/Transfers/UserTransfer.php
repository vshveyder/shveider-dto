<?php

namespace ShveiderDtoTest\CacheDTO\ModuleCache2\Transfers;

use ShveiderDto\AbstractCachedTransfer;

class UserTransfer extends AbstractCachedTransfer
{
    public ?string $name = null;

    public ?int $age = null;

    public ?AddressTransfer $address = null;
}
