
# Configure DTO manually


! ShveiderDto\AbstractTransfer can't work with private properties.

```php
<?php

namespace ShveiderDtoTest\DTO\Module2\Transfers;

use ShveiderDto\AbstractTransfer;

class UserAddRequestTransfer extends AbstractTransfer
{
    protected array $__registered_vars = ['user'];

    protected array $__registered_transfers = [
        'user' => '\ShveiderDtoTest\DTO\Module2\Transfers\UserTransfer',
    ];

    protected UserTransfer $user;
}
```

```php
<?php

namespace ShveiderDtoTest\DTO\Module2\Transfers;

use ShveiderDto\AbstractTransfer;

class UserTransfer extends AbstractTransfer
{
    protected array $__registered_vars = ['name', 'age', 'address'];

    protected array $__registered_transfers = [
        'address' => '\ShveiderDtoTest\DTO\Module2\Transfers\AddressTransfer'
    ];

    protected ?string $name = null;

    protected ?int $age = null;

    protected ?AddressTransfer $address = null;
}
```

```php
<?php

namespace ShveiderDtoTest\DTO\Module3\Transfers;

use DateTime;
use ShveiderDto\AbstractTransfer;

class UserCollectionTransfer extends AbstractTransfer
{
    protected array $__registered_vars = ['users', 'dates', 'ordersList'];

    // needs to map your array of transfers to array using toArray method.
    // needs to map array of array with fromArray method.
    protected array $__registered_array_transfers = [
        'users' => '\ShveiderDtoTest\DTO\Module2\Transfers\UserTransfer'
    ];

    protected array $users;

    /** @var array<\DateTime> */
    protected array $dates = [];

    protected array $ordersList = [];

    // if you have for example ['dates' => [['data' => 'yesterday', 'timezone' => 'UTC'], ...]] array.
    // you can add method mapArrayTo{PropertyName} to map it correct to your dates property.
    // Is using in fromArray method.
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

    public function addDate(\DateTime $dateTime): void
    {
        $this->dates[] = $dateTime;
    }
}
```