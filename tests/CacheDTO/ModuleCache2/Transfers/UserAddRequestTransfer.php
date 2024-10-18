<?php

namespace ShveiderDtoTest\CacheDTO\ModuleCache2\Transfers;

use ShveiderDto\AbstractCachedTransfer;

class UserAddRequestTransfer extends AbstractCachedTransfer
{
    public UserTransfer $user;
}
