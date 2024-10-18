<?php declare(strict_types=1);

namespace ShveiderDto\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class TransferCache
{
    public function __construct(public string $name)
    {
    }
}
