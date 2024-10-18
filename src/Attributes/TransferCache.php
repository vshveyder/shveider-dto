<?php declare(strict_types=1);

namespace ShveiderDto\Attributes;

#[\Attribute]
readonly class TransferCache
{
    public function __construct(public string $name)
    {
    }
}
