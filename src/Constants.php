<?php declare(strict_types=1);

namespace ShveiderDto;

class Constants
{
    public const CACHE_VARS = 0;
    public const CACHE_TRANSFERS = 1;
    public const CACHE_ARRAY_OF_TRANSFERS = 2;
    public const CACHE_VALUE_WITH_CONSTRUCT = 3;

    public const SHARED_SKIPPED_PROPERTIES = [
        '__modified',
        '__registered_vars',
        '__registered_transfers',
        '__registered_array_transfers',
        '__registered_ao',
        '__private_registered_vars',
        '__reflection',
        '__registered_values_with_construct',
    ];
}
