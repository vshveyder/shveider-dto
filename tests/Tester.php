<?php

namespace ShveiderDtoTest;

class Tester
{
    public function __construct(private bool $useMethod)
    {
    }

    public function assert(mixed $v): Assertion
    {
        return new Assertion($v, $this->useMethod);
    }
}
