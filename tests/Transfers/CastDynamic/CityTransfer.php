<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\CastDynamic;

use ShveiderDto\AbstractCastDynamicTransfer;

class CityTransfer extends AbstractCastDynamicTransfer
{
    public function __construct(protected string $key, protected string $name)
    {
    }
}
