<?php declare(strict_types=1);

namespace ShveiderDto;

use ShveiderDto\Helpers\TransferCacheReader;

abstract class AbstractCachedTransfer extends AbstractTransfer
{
    protected const SHARED_SKIPPED_PROPERTIES = [
        '__modified' => 0,
        '__private_registered_vars' => 1,
        '__cache' => 2,
    ];

    protected string $__cache;

    protected function hasRegisteredArrayTransfers(string $name): bool
    {
        return isset($this->__cache::CACHE[static::class][Constants::CACHE_ARRAY_OF_TRANSFERS][$name]);
    }

    protected function getRegisteredArrayTransfer(string $name): string
    {
        return $this->__cache::CACHE[static::class][Constants::CACHE_ARRAY_OF_TRANSFERS][$name];
    }

    protected function findRegisteredTransfer(string $name): ?string
    {
        return TransferCacheReader::find($this->__cache, static::class, Constants::CACHE_TRANSFERS, $name);
    }

    protected function findRegisteredVars(): array
    {
        return $this->__cache::CACHE[static::class][Constants::CACHE_VARS];
    }

    protected function hasRegisteredValueWithConstruct(string $name): bool
    {
        return isset($this->__cache::CACHE[static::class][Constants::CACHE_VALUE_WITH_CONSTRUCT][$name]);
    }

    protected function getRegisteredValueWithConstruct(string $name): array
    {
        return $this->__cache::CACHE[static::class][Constants::CACHE_VALUE_WITH_CONSTRUCT][$name];
    }
}
