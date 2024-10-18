<?php

namespace ShveiderDtoTest\CacheDTO\ModuleCache2\Transfers;

use ShveiderDto\AbstractCachedTransfer;

class AddressTransfer extends AbstractCachedTransfer
{
    public ?string $city;

    public ?string $country;

    public ?string $zip;

    public ?string $street;

    public ?int $streetNumber;
}
