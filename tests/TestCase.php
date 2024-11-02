<?php declare(strict_types=1);

namespace ShveiderDtoTest;

use ShveiderDto\AbstractTransfer;
use ShveiderDto\DataTransferObjectInterface;
use ShveiderDtoTest\VO\TestVo;

class TestCase
{
    private const ADDRESS_0 = [
        'street' => 'firstAddressStreet',
        'city' => [ // for example transfer with _construct(key: string, name: string)
            'name' => 'firstCityName',
            'key' => 'firstCityKey'
        ],
    ];

    private const ADDRESS_1 = [
        'street' => 'secondAddressStreet',
        'city' => [ // for example transfer with _construct(key: string, name: string)
            'name' => 'secondCityName',
            'key' => 'secondCityKey'
        ],
    ];

    private const CUSTOMER = [
        'name' => 'customer name',
        'email' => 'customer@mail.com',
        'phone' => '0123456789',
        'addresses' => [self::ADDRESS_0, self::ADDRESS_1],
    ];

    private Tester $tester;

    public function __construct(
        protected bool $useMethod,
        protected DataTransferObjectInterface $transfer,
        protected string $addressTransferClass,
        protected string $addressCityTransferClass,
    ) {
        $this->tester = new Tester($this->useMethod);
    }

    public function testFromArrayToArray(): void
    {
        $this->transfer->fromArray([
            'customer' => self::CUSTOMER,
            'testVo' => new TestVo('v string', 1234, [1, 2, 3]),
        ]);

        $this->tester->assert($this->transfer)
            ->transfer('customer')
            ->propEqual('customer.name', self::CUSTOMER['name'])
            ->propEqual('customer.email', self::CUSTOMER['email'])
            ->propEqual('customer.phone', self::CUSTOMER['phone'])
            ->property('customer.addresses', 'is array')
            ->property('customer.addresses', 'count', 2);

        $this->assertAddresses();

        $arrayResult = $this->transfer->toArray();
        $this->tester->assert($arrayResult)->transfer('customer');

        $arrayResultCustomer = $arrayResult['customer']->toArray();
        $this->tester->assert($arrayResultCustomer)
            ->propEqual('name', self::CUSTOMER['name'])
            ->propEqual('email', self::CUSTOMER['email'])
            ->propEqual('phone', self::CUSTOMER['phone'])
            ->property('addresses', 'is array')
            ->property('addresses', 'count', 2)
            ->transfer('addresses.0')
            ->transfer('addresses.1');

        $arrayResult = $this->transfer->toArray(true);
        $this->tester->assert($arrayResult)
            ->property('customer', 'is array')
            ->propEqual('customer.name', self::CUSTOMER['name'])
            ->propEqual('customer.email', self::CUSTOMER['email'])
            ->propEqual('customer.phone', self::CUSTOMER['phone'])
            ->property('customer.addresses', 'is array')
            ->property('customer.addresses', 'count', 2)
            ->property('customer.addresses.0', 'is array')
            ->property('customer.addresses.1', 'is array');

        $this->tester->assert($this->transfer)
            ->property('testVo', 'is_a', TestVo::class)
            ->propEqual('testVo.$vString', 'v string')
            ->propEqual('testVo.$vInt', 1234)
            ->propEqual('testVo.$vArray.0', 1)
            ->propEqual('testVo.$vArray.1', 2)
            ->propEqual('testVo.$vArray.2', 3);
    }

    public function testAddMethods(): void
    {
        /** @var class-string<\ShveiderDto\AbstractTransfer> $addressTransferClass */
        $addressTransferClass = $this->addressTransferClass;

        /** @var class-string<\ShveiderDto\AbstractTransfer> $addressCityTransferClass */
        $addressCityTransferClass = $this->addressCityTransferClass;

        $address0 = (new $addressTransferClass())->fromArray(self::ADDRESS_0);
        $address1 = new $addressTransferClass();
        $address1->setStreet(self::ADDRESS_1['street']);
        $address1->setCity(new $addressCityTransferClass(self::ADDRESS_1['city']['key'], self::ADDRESS_1['city']['name']));

        $this->transfer->getCustomer()->setAddresses([]);
        $this->tester->assert($this->transfer)->property('customer.addresses', 'count', 0);
        $this->transfer->getCustomer()->addAddress($address0);
        $this->tester->assert($this->transfer)->property('customer.addresses', 'count', 1);
        $this->transfer->getCustomer()->addAddress($address1);
        $this->tester->assert($this->transfer)->property('customer.addresses', 'count', 2);

        $this->assertAddresses();
    }

    public function testAssociativeMethods(): void
    {
        $this->transfer->fromArray(['testAssociative' => []]);
        $this->tester->assert($this->transfer)
            ->property('testAssociative.attributes', 'is array')
            ->property('testAssociative.attributes', 'count', 0);

        $this->transfer->getTestAssociative()->addAttribute('attr1', 'attr_val_1');
        $this->transfer->getTestAssociative()->addAttribute('attr2', 'attr_val_2');
        $this->transfer->getTestAssociative()->addAttribute('attr3', 'attr_val_3');

        $this->tester->assert($this->transfer)
            ->property('testAssociative.attributes', 'count', 3)
            ->propEqual('testAssociative.attributes.attr1', 'attr_val_1')
            ->propEqual('testAssociative.attributes.attr2', 'attr_val_2')
            ->propEqual('testAssociative.attributes.attr3', 'attr_val_3');
    }

    public function testValueObject(): void
    {
        $this->transfer->fromArray([
            'testVo' => ['vString' => 'val str', 'vInt' => 12345, 'vArray' => [1,2,3]],
        ]);

        $this->tester->assert($this->transfer)
            ->property('testVo', 'is_a', TestVo::class)
            ->propEqual('testVo.$vString', 'val str')
            ->propEqual('testVo.$vInt', 12345)
            ->propEqual('testVo.$vArray.0', 1)
            ->propEqual('testVo.$vArray.1', 2)
            ->propEqual('testVo.$vArray.2', 3);
    }

    public function testModifiedToArray(): void
    {
        // reset modified property for test.
        $reflectionTransfer = new \ReflectionObject($this->transfer);
        $reflectionTransfer->getProperty('__modified')->setValue($this->transfer, []);
        $modified = $this->transfer->modifiedToArray();
        $this->tester->assert($modified)
            ->is('array')
            ->is('count', 0);

        $this->transfer->fromArray([
            'customer' => [
                'name' => self::CUSTOMER['name'],
            ],
            'testVo' => new TestVo('v string', 1234, [1, 2, 3]),
        ]);

        $modified = $this->transfer->modifiedToArray();
        $this->tester->assert($modified)
            ->is('count', 2)
            ->property('customer', 'transfer')
            ->property('testVo', 'is_a', TestVo::class)
            ->propEqual('customer.name', self::CUSTOMER['name'])
            ->propertyNotInitialized('customer', 'email')
            ->propertyNotInitialized('customer', 'phone')
            ->propEqual('testVo.$vString', 'v string')
            ->propEqual('testVo.$vInt', 1234)
            ->propEqual('testVo.$vArray.0', 1)
            ->propEqual('testVo.$vArray.1', 2)
            ->propEqual('testVo.$vArray.2', 3);

        $modified = $this->transfer->modifiedToArray(true);
        $this->tester->assert($modified)
            ->is('count', 2)
            ->property('customer', 'array')
            ->property('customer', 'count', 1)
            ->property('testVo', 'is_a', TestVo::class);
    }

    protected function assertAddresses(): void
    {
        $this->tester->assert($this->transfer)
            ->transfer('customer.addresses.0')
            ->propEqual('customer.addresses.0.street', self::ADDRESS_0['street'])
            ->transfer('customer.addresses.0.city')
            ->propEqual('customer.addresses.0.city.name', self::ADDRESS_0['city']['name'])
            ->propEqual('customer.addresses.0.city.key', self::ADDRESS_0['city']['key'])

            ->transfer('customer.addresses.1')
            ->propEqual('customer.addresses.1.street', self::ADDRESS_1['street'])
            ->transfer('customer.addresses.1.city')
            ->propEqual('customer.addresses.1.city.name', self::ADDRESS_1['city']['name'])
            ->propEqual('customer.addresses.1.city.key', self::ADDRESS_1['city']['key']);

        $customer = $this->transfer->toArray()['customer'];
        $this->tester->assert($customer)->transfer();

        $addresses = $customer->toArray()['addresses'];
        $this->tester->assert($addresses)->transfer('0')->transfer('1');

        $this->tester->assert($addresses[0])
            ->propEqual('street', self::ADDRESS_0['street'])
            ->transfer('city')
            ->propEqual('city.name', self::ADDRESS_0['city']['name'])
            ->propEqual('city.key', self::ADDRESS_0['city']['key']);

        $this->tester->assert($addresses[1])
            ->propEqual('street', self::ADDRESS_1['street'])
            ->transfer('city')
            ->propEqual('city.name', self::ADDRESS_1['city']['name'])
            ->propEqual('city.key', self::ADDRESS_1['city']['key']);

        $customerR = $this->transfer->toArray(true)['customer'];
        $this->tester->assert($customerR)->isArray();
        $addressesR = $customerR['addresses'];

        $this->tester->assert($addressesR)->isArray()
            ->property('0', 'is array')
            ->property('1', 'is array');

        $this->tester->assert($addressesR[0])
            ->propEqual('street', self::ADDRESS_0['street'])
            ->property('city', 'is array')
            ->propEqual('city.name', self::ADDRESS_0['city']['name'])
            ->propEqual('city.key', self::ADDRESS_0['city']['key']);

        $this->tester->assert($addressesR[1])
            ->propEqual('street', self::ADDRESS_1['street'])
            ->property('city', 'is array')
            ->propEqual('city.name', self::ADDRESS_1['city']['name'])
            ->propEqual('city.key', self::ADDRESS_1['city']['key']);
    }
}
