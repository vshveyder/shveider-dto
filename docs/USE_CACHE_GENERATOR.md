# Use cache generator for your dto

1. Create abstract dto and extend SVTransfer
    ```php
    
    namespace MyNamespace;
    
    use ShveiderDto\SVTransfer;
    
    class MyTransfer extends SVTransfer
    {
        // determine cache transfer.
        protected string $cache = '\MyNamespace\Cache\CacheTransfer';
    }
    ```

2. Create your transfer.
    ```php
    
    namespace MyNamespace\Transfers;
    
    use MyTransfer\MyTransfer;
    
    class UserTransfer extends MyTransfer
    {
        public ?string $name = null;
    
        public ?int $age = null;
    
        public ?AddressTransfer $address = null;
    }
    ```
   
3. Add command to generate cache
   ```php
   (new \ShveiderDto\Command\GenerateDtoCacheFile(
       new \ShveiderDto\ShveiderDtoFactory(),
       new \ShveiderDto\GenerateDTOConfig(
           readFrom: __DIR__ . 'path to folder with transfers. It uses glob pattern. So You can add multiple folders.',
           writeTo:  __DIR__ . '/../src/Cache/CacheTransfer.php',
           writeToNamespace: '\MyNamespace\Cache\CacheTransfer',
       ),
   ))->execute();
   ```

4. Execute command. <br/>
   This command will generate class with cache for your transfers.
5. Use your dto and use methods from [DataTransferObjectInterface](./../src/DataTransferObjectInterface.php)


### modifiedToArray()
To use this method in this type of dto you need to send wars using `fromArray()` method.

if you set your vars like this
```php
$transfer->name = 'John';
```
This war won't be added to __modified property. <br/>

Another way
```php
$transfer->modify('name')->name = 'John';
```
In this case 'name' will be added to __modified property.

Or 
```php
$transfer->set('name', 'John');
```
In this case 'name' will be added to __modified property.
