<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Traits;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\Attributes\ArrayOf;
use ShveiderDtoTest\Transfers\Traits\Generated\TestAssociativeTransferTrait;

class TestAssociativeTransfer extends AbstractConfigurableTransfer
{
    use TestAssociativeTransferTrait;

    #[ArrayOf('string', 'attribute', true)]
    protected array $attributes = [];
}
