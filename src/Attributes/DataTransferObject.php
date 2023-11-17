<?php

namespace ShveiderDto\Attributes;

use Attribute;

/**
 * Not Implemented
 */
#[Attribute]
class DataTransferObject
{
    public function __construct(
        private readonly string $name,
        private readonly array $properties,
    ) {
    }
}
