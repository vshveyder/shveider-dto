<?php declare(strict_types=1);

namespace ShveiderDto\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class ArrayOf
{
    public function __construct(
        public string $type,
        public string $singular = '',
        public bool $associative = false,
    ) {
    }
}
