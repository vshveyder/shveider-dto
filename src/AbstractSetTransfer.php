<?php declare(strict_types=1);

namespace ShveiderDto;

use ShveiderDto\Traits\ModifiedOverrideTrait;

/**
 * @property array<string> $__registered_vars
 * - Uses for mapping fields in helping methods. If not set - get_class_vars is used.
 *
 * @property array<string, string> $__registered_transfers
 * - Uses to determine which field is transfer. To map it correctly.
 *
 * @property array<string, string> $__registered_array_transfers
 * - Uses to determine which field is array of transfers. To map it correctly.
 *
 * @property array<string, array<string>> $__registered_values_with_construct
 * - Uses to determine which field is transfer with fields in construct. To map it correctly.
 *
 */
abstract class AbstractSetTransfer extends AbstractConfigurableTransfer
{
    use ModifiedOverrideTrait;
}
