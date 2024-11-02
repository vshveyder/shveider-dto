<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Cast;

use ShveiderDto\AbstractCastTransfer;

class CityTransfer extends AbstractCastTransfer
{
    public function __construct(public string $key, public string $name)
    {
    }
}
