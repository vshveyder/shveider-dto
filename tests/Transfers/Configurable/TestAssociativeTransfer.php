<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Configurable;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\Attributes\ArrayOf;

class TestAssociativeTransfer extends AbstractConfigurableTransfer
{
    #[ArrayOf('string', 'attribute', true)]
    public array $attributes = [];
}
