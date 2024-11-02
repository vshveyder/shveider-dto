<?php declare(strict_types=1);

namespace ShveiderDtoTest\Transfers\Traits;

use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDtoTest\Transfers\Traits\Generated\CityTransferTrait;

class CityTransfer extends AbstractConfigurableTransfer
{
    use CityTransferTrait;

    public function __construct(protected string $key, protected string $name)
    {
    }
}
