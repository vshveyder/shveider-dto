# AbstractCachedTransfer

The `AbstractCachedTransfer` class is a specialized form of the AbstractTransfer, designed to optimize performance by using cached metadata. The cache, which includes information about properties and configurations for the transfer objects, is generated separately and used by the parent class for efficient data handling.


### How it works:

- A cache file (e.g., `TransferCache`) is generated, containing all necessary information about the fields and methods of the DTO.
- This allows the `AbstractCachedTransfer` to operate without setters/getters while using the cached metadata to manage properties.
- Also, this allows to use method like fromArray/toArray with correct mapping.

### Example:

```php
namespace ShveiderDtoTest\CacheDTO\ModuleCache2\Transfers;

use ShveiderDto\AbstractCachedTransfer;use ShveiderDto\Attributes\TransferCache;
#[TransferCache('YourOwnCacheTransferName')] // (optional) if you want to have different name for cache class for some classes.
class AddressTransfer extends AbstractCachedTransfer
{
    public ?string $city;
    public ?string $country;
    public ?string $zip;
    public ?string $street;
    public ?int $streetNumber;
    public UserTransfer $user;
    public array $users;

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

Generate cache class
```php
(new GenerateDtoCacheFile(
    new ShveiderDtoFactory(),
    new GenerateDTOConfig(
        readFrom: __DIR__ . '/../tests/CacheDTO/*/Transfers', // dir or dirs where your DTOs placed (is read by blob functions)
        writeTo:  __DIR__ . '/../src/Cache/', // directory where cache class/classes will be placed.
        writeToNamespace: 'ShveiderDto\Cache', // namespace of cache class or classes.
    )
))->execute();
```

Then you can use it like here: [example of cached transfer usage](../tests/cases/cache/test-from-and-to-array.php) 

In this example, the cache stores information about the properties (id, name, age) without needing to process it at runtime.

## Key Points:
 - Performance: Fast, as it avoids the overhead of reflection.
 - Usage: Best suited for DTOs that are used frequently and need high performance.
---