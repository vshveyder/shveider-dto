<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Cached;

class CityTransfer extends ProjectLevelAbstractCachedTransfer
{
    public function __construct(public string $key, public string $name)
    {
    }
}
