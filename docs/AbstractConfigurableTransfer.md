# AbstractConfigurableTransfer

`AbstractConfigurableTransfer` provides a more structured approach to DTOs, allowing developers to configure traits that handle setters, getters, and adders for properties.

### How it works:

- You placed some transfers in you project that extends AbstractConfigurableTransfer.
- Run `ShveiderDto\GenerateDtoTraitsCommand`.
- use generated traits in you transfers.
- Traits define the configuration of the DTO, including which fields are available and how they can be accessed or modified.
- Setters, getters, and adders are automatically generated based on the trait configurations.
- Traits can't use private properties. Only public and protected.

### Simple example of generated trait:

Add DTO
```php
class SomeResourceTransfer extends AbstractConfigurableTransfer
{
    protected int $id;

    protected ?string $firstName;

    #[ValueWithConstruct]
    protected TransferWithConstruct $transferWithConstruct;

    #[ValueWithConstruct]
    protected ValueObjectWithConstruct $objectWithConstruct;
    
    #[ArrayOf(SomeResourceTransfer::class, 'resource')]
    protected array $resources = [];

    #[ArrayOf('self')]
    protected array $children = [];
}
```

Run Command 
```php
// traits will be placed near transfers in subdirectory `Generated`. You can change this directory name by config. 
$sharedConfig = new GenerateDTOConfig(
    __DIR__ . '/../tests/DTO/*/Transfers',
    minified: false,
);

echo PHP_EOL . 'Generate Transfer\'s.' . PHP_EOL;

(new GenerateDtoTraitsCommand(new ShveiderDtoFactory(), $sharedConfig))->execute();

// or

// all traits will be to  __DIR__ . '/../tests/Generated',
$modulesConfig = new GenerateDTOConfig(
    __DIR__ . '/../tests/DTO/*/Transfers',
    __DIR__ . '/../tests/Generated',
    'ShveiderDtoTest\Generated',
    minified: false,
);

(new GenerateDtoTraitsCommand(new ShveiderDtoFactory(), $modulesConfig))->execute();

```

- It will generate setters/getters/adders.
- Adders will be generated for vars with ArrayOf attribute
- ValueWithConstruct use to tell that we have some values in construct

Then add 
```php
use SomeResourceTransferTrait;
```
And use your transfer.

### Key Points:
- Performance: Moderate, faster than reflection-based DTOs but slightly slower than cast-based DTOs.
- Usage: Best suited for situations where you need structured access to properties and the ability to add new behavior via traits.