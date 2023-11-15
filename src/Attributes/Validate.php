<?php

namespace ShveiderDto\Attributes;

#[\Attribute]
readonly class Validate
{
    public function __construct(
        public ?bool $required = false,
        public ?int  $minLength = null,
        public ?int  $maxLength = null,
    ) {
    }
}