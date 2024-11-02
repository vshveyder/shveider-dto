<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Configurable;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\Attributes\ArrayOf;

class CustomerTransfer extends AbstractConfigurableTransfer
{
    protected array $__registered_array_transfers = [
        'addresses' => AddressTransfer::class,
    ];

    public string $name;

    public string $email;

    public string $phone;

    #[ArrayOf(AddressTransfer::class, 'address')]
    public array $addresses;
}
