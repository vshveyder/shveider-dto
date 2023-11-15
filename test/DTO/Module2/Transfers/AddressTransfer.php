<?php

namespace ShveiderDtoTest\DTO\Module2\Transfers;

use ShveiderDto\AbstractTransfer;
use ShveiderDtoTest\Generated\AddressTransferTrait;

class AddressTransfer extends AbstractTransfer
{
    use AddressTransferTrait;

    protected ?string $city;

    protected ?string $country;

    protected ?string $zip;

    protected ?string $street;

    protected ?int $streetNumber;
}
