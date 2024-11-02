<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Cached;

use ShveiderDto\Attributes\ArrayOf;

class CustomerTransfer extends ProjectLevelAbstractCachedTransfer
{
    public string $name;

    public string $email;

    public string $phone;

    #[ArrayOf(AddressTransfer::class, 'address')]
    public array $addresses;
}
