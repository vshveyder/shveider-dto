# PHP Data Transfer Object
***

This Library helps to manage your DTO classes.

### There is several ways to use.
- [Create your DTO and just use GenerateDtoTraitsCommand.php to generate getters and setters.](docs/USING_TRAITS.md)
- [Create your DTO and extend AbstractReflectionTransfer.](docs/USING_REFLECTION.md)
- [Create your DTO and extend AbstractTransfer. But with several configurations inside your DTO.](docs/MANUALLY_CONFIGURED_TRANSFER.md)
- [Use optimized cache for your transfer.](docs/USE_CACHE_GENERATOR.md)

Also, you can configure your DTO using attributes in `\ShveiderDto\Attributes\\` namespace.

DTO has methods described in interface [DataTransferObjectInterface.php](src%2FDataTransferObjectInterface.php)
```php
    /** - Takes values from array and set it to defined properties in your data transfer object. */
    public function fromArray(array $data): static;

    /** - Takes properties in your data transfer object and returns it ass array key => value. */
    public function toArray(bool $recursive = false): array;

    /**
     *  - Takes modified properties in your data transfer object and returns it ass array key => value.
     *  - Modified properties: properties that was modified by fromArray and set* method.
     */
    public function modifiedToArray(): array;

    /** - Calls toArray method inside and convert it to json string. */
    public function toJson(bool $pretty = false): string;
```

All classes in library are extendable. You are able to extend it on your project level and modify.

# Get Started:

#### Attributes
```php
<?php

namespace ShveiderDtoTest\DTO\Module3\Transfers;

use DateTime;
use ShveiderDto\AbstractTransfer;
use ShveiderDto\Attributes\ArrayOf;
use ShveiderDtoTest\DTO\Module3\Transfers\Generated\UserCollectionTransferTrait;

class UserCollectionTransfer extends AbstractTransfer
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
```

trait that will be generated for this attributes

```php
<?php

namespace ShveiderDtoTest\DTO\Module3\Transfers\Generated;

/** Auto generated class. Do not change anything here. */
trait UserCollectionTransferTrait
{
	protected array $__registered_vars = ['users', 'dates', 'ordersList'];

	protected array $__registered_transfers = [];

	protected array $__registered_array_transfers = ['users' => '\ShveiderDtoTest\DTO\Module2\Transfers\UserTransfer'];

	/** @return array<\ShveiderDtoTest\DTO\Module2\Transfers\UserTransfer> */
	public function getUsers(): array { return $this->users;}
	public function setUsers(array $v): static { $this->__modified['users'] = true; $this->users = $v; return $this;}
	/** @return array<\DateTime> */
	public function getDates(): array { return $this->dates;}
	public function setDates(array $v): static { $this->__modified['dates'] = true; $this->dates = $v; return $this;}
	/** @return array<array> */
	public function getOrdersList(): array { return $this->ordersList;}
	public function setOrdersList(array $v): static { $this->__modified['ordersList'] = true; $this->ordersList = $v; return $this;}
	public function addUser(\ShveiderDtoTest\DTO\Module2\Transfers\UserTransfer $v): static { $this->__modified['users'] = true; $this->users[] = $v; return $this;}
	public function addDate(\DateTime $v): static { $this->__modified['dates'] = true; $this->dates[] = $v; return $this;}
	public function addOrderData(array $v): static { $this->__modified['ordersList'] = true; $this->ordersList[] = $v; return $this;}
}
```

#### Create your DTO and extend AbstractTransfer. But with several configurations inside your DTO.

Examples: 
