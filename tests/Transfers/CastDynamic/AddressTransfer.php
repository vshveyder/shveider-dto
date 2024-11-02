<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\CastDynamic;

use ShveiderDto\AbstractCastDynamicTransfer;
use ShveiderDto\Attributes\ValueWithConstruct;

class AddressTransfer extends AbstractCastDynamicTransfer
{
    protected array $__casts = [
        'transfers' => ['city' => CityTransfer::class],
        'constructs' => ['city' => ['key', 'name']],
    ];

    protected string $street;

    #[ValueWithConstruct]
    protected CityTransfer $city;
}