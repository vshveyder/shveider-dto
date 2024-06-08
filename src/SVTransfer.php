<?php

namespace ShveiderDto;

abstract class SVTransfer extends AbstractTransfer
{
    protected string $cache = '\ShveiderDto\Cache\TransferCache';

    public function set(string $name, mixed $value): static
    {
        $this->$name = $value;

        return $this->modify($name);
    }

    protected function hasRegisteredArrayTransfers(string $name): bool
    {
        return isset($this->cache::CACHE[static::class][Constants::CACHE_ARRAY_OF_TRANSFERS][$name]);
    }

    protected function getRegisteredArrayTransfers(string $name): string
    {
        return $this->cache::CACHE[static::class][Constants::CACHE_ARRAY_OF_TRANSFERS][$name];
    }

    protected function hasRegisteredTransfers(string $name): bool
    {
        return isset($this->cache::CACHE[static::class][Constants::CACHE_TRANSFERS][$name]);
    }

    protected function getRegisteredTransfers(string $name): string
    {
        return $this->cache::CACHE[static::class][Constants::CACHE_TRANSFERS][$name];
    }

    protected function hasRegisteredVars(): bool
    {
        return isset($this->cache::CACHE[static::class][Constants::CACHE_VARS]);
    }

    protected function getRegisteredVars(): array
    {
        return $this->cache::CACHE[static::class][Constants::CACHE_VARS];
    }

    public function modify(string $name): static
    {
        $this->__modified[$name] = true;

        return $this;
    }
}
