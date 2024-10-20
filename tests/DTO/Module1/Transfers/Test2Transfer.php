<?php

namespace ShveiderDtoTest\DTO\Module1\Transfers;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDtoTest\DTO\Module1\Transfers\Generated\Test2TransferTrait;

class Test2Transfer extends AbstractConfigurableTransfer
{
    use Test2TransferTrait;

    protected string $name;

    protected ?string $firstName;

    protected \DateTime $dateTime;

    protected ?\DateTime $fullDate;
}
