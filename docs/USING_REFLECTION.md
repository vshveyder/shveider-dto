# Create your DTO and extend AbstractReflectionTransfer.

#### *Warning:* Using reflection is a difficult operation for PHP. This may have a negative impact on performance.

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

Then you can use methods Defined in [DataTransferObjectInterface.php](..%2Fsrc%2FDataTransferObjectInterface.php)

## Example:

```php
$reflectionTransfer = new MyReflectionTransfer();

$data = [
    'name' => 'SomeName',
    'firstName' => 'testFirstname',
    'dateTime' => new DateTime(),
    'fullDate' => null,
    'city' => 'Odessa',
    'country' => 'Ukraine',
    'zip' => '33061',
    'street' => 'Test Street',
    'streetNumber' => 123,
];

$reflectionTransfer->fromArray($data);

var_dump($reflectionTransfer->toArray()); // print data array

$reflectionTransfer->setName('some another name');
$reflectionTransfer->getName(); // return name
$reflectionTransfer->modifiedToArray(); // return only modified properties.
$reflectionTransfer->modifiedToArray(recurcive: true); // return only modified properties. convert all child transfers to array.
$reflectionTransfer->toJson(pretty: false) // print json.
```