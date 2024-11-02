<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Cast;

use ShveiderDto\AbstractCastTransfer;
use ShveiderDto\Attributes\ValueWithConstruct;

class AddressTransfer extends AbstractCastTransfer
{
    protected array $__casts = [
        'transfers' => ['city' => CityTransfer::class],
        'constructs' => ['city' => ['key', 'name']],
    ];

    public string $street;

    #[ValueWithConstruct]
    public CityTransfer $city;
}