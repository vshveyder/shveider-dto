<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Cast;

use ShveiderDto\AbstractCastTransfer;
use ShveiderDto\Attributes\ArrayOf;

class TestAssociativeTransfer extends AbstractCastTransfer
{
    #[ArrayOf('string', 'attribute', true)]
    public array $attributes = [];
}
