<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Cached;

use ShveiderDto\Attributes\ValueWithConstruct;

class AddressTransfer extends ProjectLevelAbstractCachedTransfer
{
    public string $street;

    #[ValueWithConstruct]
    public CityTransfer $city;
}