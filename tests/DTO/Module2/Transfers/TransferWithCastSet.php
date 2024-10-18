<?php declare(strict_types=1);

namespace ShveiderDtoTest\DTO\Module2\Transfers;

use ShveiderDto\AbstractCastTransfer;
use ShveiderDto\Attributes\TransferSkip;

#[TransferSkip]
class TransferWithCastSet extends AbstractCastTransfer
{
    protected array $__casts = [
        'collections' => [
            'users' => UserTransfer::class,
        ],
    ];

    protected string $name;

    protected string $description;

    protected array $images;

    protected array $users;
}
