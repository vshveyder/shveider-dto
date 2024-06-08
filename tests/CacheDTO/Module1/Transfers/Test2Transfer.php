<?php

namespace ShveiderDtoTest\CacheDTO\Module1\Transfers;

use ShveiderDto\SVTransfer;

class Test2Transfer extends SVTransfer
{
    public string $name;

    public ?string $firstName;

    public \DateTime $dateTime;

    public ?\DateTime $fullDate;
}
