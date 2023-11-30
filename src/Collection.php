<?php

namespace ShveiderDto;

use ArrayObject;

class Collection extends ArrayObject
{
    public function toArray(bool $recursive = false): array
    {
        return array_map(fn (AbstractTransfer $transfer) => $transfer->toArray($recursive), $this->getArrayCopy());
    }
}
