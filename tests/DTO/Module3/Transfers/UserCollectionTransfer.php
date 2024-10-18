<?php

namespace ShveiderDtoTest\DTO\Module3\Transfers;

use DateTime;
use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\Attributes\ArrayOf;
use ShveiderDtoTest\DTO\Module3\Transfers\Generated\UserCollectionTransferTrait;

class UserCollectionTransfer extends AbstractConfigurableTransfer
{
    use UserCollectionTransferTrait;

    #[ArrayOf(type: '\ShveiderDtoTest\DTO\Module2\Transfers\UserTransfer', singular: 'user')]
    protected array $users;

    #[ArrayOf(type: DateTime::class, singular: 'date')]
    protected array $dates = [];

    #[ArrayOf('array', 'orderData')]
    protected array $ordersList = [];

    public function mapArrayToDates(array $date): void
    {
        $this->dates = [];

        foreach ($date as $item) {
            $this->dates[] = new DateTime(
                $item['date'] ?? 'now',
                    isset($item['timezone']) ? new \DateTimeZone($item['timezone']): null
            );
        }
    }
}
