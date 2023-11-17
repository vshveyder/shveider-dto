<?php

namespace ShveiderDtoTest\DTO\Module2\Transfers;

use ShveiderDto\AbstractTransfer;
use ShveiderDtoTest\DTO\Module2\Transfers\Generated\UserAddRequestTransferTrait;

class UserAddRequestTransfer extends AbstractTransfer
{
    use UserAddRequestTransferTrait;

    protected UserTransfer $user;
}
