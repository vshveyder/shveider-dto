<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Configurable;

use ShveiderDto\AbstractConfigurableTransfer;

class CityTransfer extends AbstractConfigurableTransfer
{
    public function __construct(public string $key, public string $name)
    {
    }
}
