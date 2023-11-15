<?php

namespace ShveiderDto\Attributes;

#[\Attribute]
readonly class ArrayOf
{
    public function __construct(public string $type, public string $singular = '')
    {
    }
}
