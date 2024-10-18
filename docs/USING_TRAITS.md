# Get started

### Create cli command in your project. For example: make:transfers.

Traits can't use private properties. Only public and protected.

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ShveiderDto\Command\GenerateDtoTraitsCommand;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\ShveiderDtoFactory;

class TransferGenerate extends Command
{
    protected $signature = 'make:transfers';
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new GenerateDtoTraitsCommand(
            new ShveiderDtoFactory(),
            new GenerateDTOConfig(
                app_path('DTO'), // directory where you placed your transfers.
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

### Create DTO class.

```php
<?php

use ShveiderDto\AbstractConfigurableTransfer;

class UserTransfer extends AbstractConfigurableTransfer
{
    protected ?string $name = null;

    protected ?int $age = null;

    protected ?AddressTransfer $address = null; // some another transfer you may create
}
```

### Run command `make:transfers`

Command will generate traits for objects that extend `ShveiderDto\AbstractConfigurableTransfer` and placed in directory you mentioned in cli command.

### Add generated trait to your Transfer.
```php
<?php

use ShveiderDto\AbstractConfigurableTransfer;
use Generated\UserTransferTrait;

class UserTransfer extends AbstractConfigurableTransfer
{
    use UserTransferTrait; // <- add trait.

    protected ?string $name = null;

    protected ?int $age = null;

    protected ?AddressTransfer $address = null;
}
```

### Now you can use it.
```php
<?php

$userTransfer = new UserTransfer();
$userTransfer->fromArray([
    'name' => 'php',
    'age' => 2,
    'address' => []
]);

echo $userTransfer->getName(); // print php
echo $userTransfer->getAge(); // print 2
$userTransfer->getAddress(); // return AddressTransfer.
```
