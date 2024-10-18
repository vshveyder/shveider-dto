# AbstractCastTransfer

`AbstractCastTransfer` provides a simplified DTO mechanism using casting, with no built-in setters or getters. It is designed to be lean and fast but with fewer customization options.

### How it works:

- Properties can be directly accessed and modified through casting.
- Unlike configurable DTOs, there are no getters or setters. This makes the DTO lightweight and performant, but with less structure.

### Example:

```php
class UserTransfer extends AbstractCastTransfer
{
    protected $__cast = [
        'transfers' => ['customer' => CustomerTransfer::class],
        'constructs' => ['delivery' => [Delivery::class, 'street', 'number']],
        'collections' => ['fiends' => CustomerTransfer::class]
    ];

    public string $name;

    public int $age;
    
    // for example, it is array of  CustomerTransfer's
    public array $fiends;
    
    public CustomerTransfer $customer;
    
    // for example this class has construct with two fields (street, number)
    public Delivery $delivery;
}
```
In this case when you will try to fill this transfer from array using `$transfer->fromArray($request)` method. It will be mapped correctly.

### Key Points:
- Performance: Extremely fast due to its simplicity.
- Usage: Suitable for applications where performance is critical and customization through setters/getters is not needed.