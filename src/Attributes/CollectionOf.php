<?php
// Not Implemented.
namespace ShveiderDto\Attributes;

#[\Attribute]
class CollectionOf
{
    public function __construct(public readonly string $type, public readonly string $singular)
    {
    }
}
