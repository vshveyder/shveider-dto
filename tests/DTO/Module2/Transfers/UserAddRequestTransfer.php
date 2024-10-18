<?php

namespace ShveiderDtoTest\DTO\Module2\Transfers;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDtoTest\DTO\Module2\Transfers\Generated\UserAddRequestTransferTrait;

class UserAddRequestTransfer extends AbstractConfigurableTransfer
{
    use UserAddRequestTransferTrait;

    protected UserTransfer $user;
}
