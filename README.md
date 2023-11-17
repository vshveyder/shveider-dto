# PHP Data Transfer Object
***

### There is several ways to use this library.
- Create your DTO and extend AbstractReflectionTransfer.
- Create your DTO and extend AbstractTransfer. But with several configurations inside your DTO.
- Create your DTO and extend AbstractTransfer. And use GenerateDtoTraitsCommand.php to generate trait that adds getters/setters/configs to your DTO.
- Create your DTO and just use GenerateDtoTraitsCommand.php to generate getters and setters.

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

***
#### Create your DTO and extend AbstractReflectionTransfer.

```php
namespace YourNamespace\Transfers;

use ShveiderDto\AbstractReflectionTransfer;

class SomeTransfer extends AbstractReflectionTransfer
{
    protected string $firstName;

    public string $lastName;

    private ?int $age;
}
```
***

#### Create your DTO and extend AbstractTransfer. But with several configurations inside your DTO.

Examples: 

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

#### Create your DTO and extend AbstractTransfer. And use GenerateDtoTraitsCommand.php to generate trait that adds getters/setters/configs to your DTO.

1. Create Command in your framework.

Example with laravel.
you have
   app/DTO directory where you placed your transfers.

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ShveiderDto\Command\GenerateDtoTraitsCommand;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\ShveiderDtoFactory;

class TransferGenerate extends Command
{
    protected $signature = 'app:transfer-generate';
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new GenerateDtoTraitsCommand(
            new ShveiderDtoFactory(),
            new GenerateDTOConfig(
                app_path('DTO'),
                app_path('Generated'), // directory for generated transfer traits.
                'YourNamespace\App\Generated', // namespace for generated transfer traits. 
                minified: true,
            ),
        ))->execute();
    }
    
    /**
     * If you have next structure in your project.
     * /app
     *     /SomeModule
     *          /Transfers
     *              /MyTransfer.php
     *     /SomeModule2
     *          /Transfers
     *              /AnotherTransfer.php
     * This command will find your transfer and will create dir `Generated`
     * inside Transfers dirs with traits.
     * 
     */
    public function handle2()
    {
        (new GenerateDtoTraitsCommand(new ShveiderDtoFactory(), new GenerateDTOConfig(
            app_path('*/Transfers'),
            minified: true,
        )))->execute();
    }
}
```

2. Create Your Transfer

```php
<?php

namespace ShveiderDtoTest\DTO\Module2\Transfers;

use ShveiderDto\AbstractTransfer;

class UserTransfer extends AbstractTransfer
{
    protected ?string $name = null;

    protected ?int $age = null;

    protected ?AddressTransfer $address = null;
}
```

3. run command.
    Command will generate next trait
```php
<?php

namespace ShveiderDtoTest\DTO\Module2\Transfers\Generated;

/** Auto generated class. Do not change anything here. */
trait UserTransferTrait
{
	protected array $__registered_vars = ['name', 'age', 'address'];

	protected array $__registered_transfers = ['address' => '\ShveiderDtoTest\DTO\Module2\Transfers\AddressTransfer'];

	protected array $__registered_array_transfers = [];

	public function getName(): ?string { return $this->name;}
	public function setName(?string $v): static { $this->__modified['name'] = true; $this->name = $v; return $this;}
	public function getAge(): ?int { return $this->age;}
	public function setAge(?int $v): static { $this->__modified['age'] = true; $this->age = $v; return $this;}
	public function getAddress(): ?\ShveiderDtoTest\DTO\Module2\Transfers\AddressTransfer { return $this->address;}
	public function setAddress(?\ShveiderDtoTest\DTO\Module2\Transfers\AddressTransfer $v): static { $this->__modified['address'] = true; $this->address = $v; return $this;}
}

```

4. Add trait to transfer

```php
<?php

namespace ShveiderDtoTest\DTO\Module2\Transfers;

use ShveiderDto\AbstractTransfer;
use ShveiderDtoTest\DTO\Module2\Transfers\Generated\UserTransferTrait;

class UserTransfer extends AbstractTransfer
{
    use UserTransferTrait;

    protected ?string $name = null;

    protected ?int $age = null;

    protected ?AddressTransfer $address = null;
}
```

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