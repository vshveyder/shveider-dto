<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Configurable;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\Attributes\ValueWithConstruct;

class AddressTransfer extends AbstractConfigurableTransfer
{
    protected array $__registered_transfers = [
        'city' => CityTransfer::class,
    ];

    protected $__registered_values_with_construct = [
        'city' => ['key', 'name']
    ];

    public string $street;

    #[ValueWithConstruct]
    public CityTransfer $city;
}