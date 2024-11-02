<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Traits;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\Attributes\ArrayOf;
use ShveiderDtoTest\Transfers\Traits\Generated\CustomerTransferTrait;

class CustomerTransfer extends AbstractConfigurableTransfer
{
    use CustomerTransferTrait;

    protected string $name;

    protected string $email;

    protected string $phone;

    #[ArrayOf(AddressTransfer::class, 'address')]
    protected array $addresses;
}
