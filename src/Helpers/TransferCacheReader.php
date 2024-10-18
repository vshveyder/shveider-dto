<?php

namespace ShveiderDto\Helpers;

class TransferCacheReader
{
    /**
     * @param class-string $cacheClass
     * @param class-string<\ShveiderDto\AbstractCachedTransfer> $transferClass
     * @param int $key
     * @param string $name
     *
     * @return mixed
     */
    public static function find(string $cacheClass, string $transferClass, int $key, string $name): mixed
    {
        return $cacheClass::CACHE[$transferClass][$key][$name] ?? null;
    }
}
