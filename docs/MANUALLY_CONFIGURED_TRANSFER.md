
# Configure DTO manually


! ShveiderDto\AbstractConfigurableTransfer can't work with private properties.

```php
<?php

namespace ShveiderDtoTest\DTO\Module2\Transfers;

use ShveiderDto\AbstractConfigurableTransfer;

class UserAddRequestTransfer extends AbstractConfigurableTransfer
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

use ShveiderDto\AbstractConfigurableTransfer;

class UserTransfer extends AbstractConfigurableTransfer
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
use ShveiderDto\AbstractConfigurableTransfer;

class UserCollectionTransfer extends AbstractConfigurableTransfer
{
    protected array $__registered_vars = ['users', 'dates', 'ordersList'];

    protected array $__registered_values_with_construct = [
        'dates' => [DateTime::class, 'date']
    ];

    // needs to map your array of transfers to array using toArray method.
    // needs to map array of array with fromArray method.
    protected array $__registered_array_transfers = [
        'users' => '\ShveiderDtoTest\DTO\Module2\Transfers\UserTransfer'
    ];

    protected array $users;

    /** @var array<\DateTime> */
    protected array $dates = [];

    protected array $ordersList = [];

    public function addDate(\DateTime $dateTime): void
    {
        $this->dates[] = $dateTime;
    }
}
```