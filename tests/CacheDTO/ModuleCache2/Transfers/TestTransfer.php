<?php

namespace ShveiderDtoTest\CacheDTO\ModuleCache2\Transfers;

use ShveiderDto\AbstractCachedTransfer;
use ShveiderDto\Attributes\TransferCache;

#[TransferCache('TestTransferCache')]
class TestTransfer extends AbstractCachedTransfer
{
    public int $id;
    public string $cache;
    public object $make;

    public object $mock;
}
