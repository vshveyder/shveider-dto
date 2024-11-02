<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Cast;

use ShveiderDto\AbstractCastTransfer;
use ShveiderDto\Attributes\ArrayOf;

class CustomerTransfer extends AbstractCastTransfer
{
    protected array $__casts = [
        'collections' => [
            'addresses' => AddressTransfer::class,
        ],
    ];

    public string $name;

    public string $email;

    public string $phone;

    #[ArrayOf(AddressTransfer::class, 'address')]
    public array $addresses;
}
