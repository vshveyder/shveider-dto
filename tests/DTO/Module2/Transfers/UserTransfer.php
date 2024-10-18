<?php

namespace ShveiderDtoTest\DTO\Module2\Transfers;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDtoTest\DTO\Module2\Transfers\Generated\UserTransferTrait;

class UserTransfer extends AbstractConfigurableTransfer
{
    use UserTransferTrait;

    protected ?string $name = null;

    protected ?int $age = null;

    protected ?AddressTransfer $address = null;
}
