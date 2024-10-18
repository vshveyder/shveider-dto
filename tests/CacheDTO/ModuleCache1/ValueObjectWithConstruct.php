<?php

namespace ShveiderDtoTest\CacheDTO\ModuleCache1;

class ValueObjectWithConstruct
{
    public function __construct(protected string $stringValue, protected int $intValue)
    {
    }

    public function getStringValue(): string
    {
        return $this->stringValue;
    }

    public function getIntValue(): int
    {
        return $this->intValue;
    }
}
