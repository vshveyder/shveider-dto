<?php

namespace ShveiderDtoTest\CacheDTO\Module2\Transfers;

use ShveiderDto\SVTransfer;

class AddressTransfer extends SVTransfer
{
    public ?string $city;

    public ?string $country;

    public ?string $zip;

    public ?string $street;

    public ?int $streetNumber;
}
