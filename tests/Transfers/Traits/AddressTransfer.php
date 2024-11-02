<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Traits;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\Attributes\ValueWithConstruct;
use ShveiderDtoTest\Transfers\Traits\Generated\AddressTransferTrait;

class AddressTransfer extends AbstractConfigurableTransfer
{
    use AddressTransferTrait;

    protected string $street;

    #[ValueWithConstruct]
    protected CityTransfer $city;
}
