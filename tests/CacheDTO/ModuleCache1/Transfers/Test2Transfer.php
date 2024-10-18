<?php

namespace ShveiderDtoTest\CacheDTO\ModuleCache1\Transfers;

use ShveiderDto\AbstractCachedTransfer;

class Test2Transfer extends AbstractCachedTransfer
{
    public string $name;

    public ?string $firstName;

    public \DateTime $dateTime;

    public ?\DateTime $fullDate;
}
