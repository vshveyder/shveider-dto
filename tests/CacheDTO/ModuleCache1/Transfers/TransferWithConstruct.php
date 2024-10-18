<?php

namespace ShveiderDtoTest\CacheDTO\ModuleCache1\Transfers;

use ShveiderDto\AbstractCachedTransfer;

class TransferWithConstruct extends AbstractCachedTransfer
{
    protected ?string $strangeField = null;

    public function __construct(protected string $stringValue, protected int $intValue, protected array $someArray)
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

    public function getSomeArray(): array
    {
        return $this->someArray;
    }

    public function getStrangeField(): ?string
    {
        return $this->strangeField;
    }
}
