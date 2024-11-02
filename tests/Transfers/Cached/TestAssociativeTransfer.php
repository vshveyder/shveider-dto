<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Cached;

use ShveiderDto\Attributes\ArrayOf;

class TestAssociativeTransfer extends ProjectLevelAbstractCachedTransfer
{
    #[ArrayOf('string', 'attribute', true)]
    public array $attributes = [];
}
