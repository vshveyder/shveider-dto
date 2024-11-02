<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\CastDynamic;

use ShveiderDto\AbstractCastDynamicTransfer;
use ShveiderDto\Attributes\ArrayOf;

class CustomerTransfer extends AbstractCastDynamicTransfer
{
    protected array $__casts = [
        'collections' => [
            'addresses' => AddressTransfer::class,
        ],
    ];

    protected string $name;

    protected string $email;

    protected string $phone;

    #[ArrayOf(AddressTransfer::class, 'address')]
    protected array $addresses;
}
