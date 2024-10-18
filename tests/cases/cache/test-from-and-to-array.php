<?php

use ShveiderDtoTest\CacheDTO\ModuleCache2\Transfers\AddressTransfer;
use ShveiderDtoTest\CacheDTO\ModuleCache2\Transfers\UserTransfer;

/** TEST fromArray method - start */
$userTransfer = new UserTransfer();

$userTransfer->fromArray([
    'name' => 'userName',
    'age' => 11,
    'address' => [
        'city' => 'someCity',
        'country' => 'someCountry',
        'zip' => 'someZip',
        'street' => 'someStreet',
        'streetNumber' => 12,
    ],
]);

assert($userTransfer->name === 'userName');
assert($userTransfer->age === 11);
assert(!is_null($userTransfer->address));
assert($userTransfer->address->city === 'someCity');
assert($userTransfer->address->country === 'someCountry');
assert($userTransfer->address->zip === 'someZip');
assert($userTransfer->address->street === 'someStreet');
assert($userTransfer->address->streetNumber === 12);
/** TEST fromArray method - end */


/** TEST toArray method - start */
$userTransfer = new UserTransfer();
$userTransfer->name = 'userName';
$userTransfer->age = 12;
$userTransfer->address = new AddressTransfer();
$userTransfer->address->city = 'someCity';
$userTransfer->address->country = 'someCountry';
$userTransfer->address->zip = 'someZip';
$userTransfer->address->street = 'someStreet';
$userTransfer->address->streetNumber = 12;

$userArray = $userTransfer->toArray();
assert(is_array($userArray) && !empty($userArray));
assert($userArray['name'] === $userTransfer->name && $userArray['name'] === 'userName');
assert($userArray['age'] === $userTransfer->age && $userArray['age'] === 12);
assert($userArray['address'] instanceof AddressTransfer);

$userAddressTransfer = $userArray['address'];
assert($userAddressTransfer->city === 'someCity');
assert($userAddressTransfer->country === 'someCountry');
assert($userAddressTransfer->zip === 'someZip');
assert($userAddressTransfer->street === 'someStreet');
assert($userAddressTransfer->streetNumber === 12);

$userAddressArray = $userAddressTransfer->toArray();
assert($userAddressArray['city'] === 'someCity');
assert($userAddressArray['country'] === 'someCountry');
assert($userAddressArray['zip'] === 'someZip');
assert($userAddressArray['street'] === 'someStreet');
assert($userAddressArray['streetNumber'] === 12);

$userArray = $userTransfer->toArray(true);
assert(is_array($userArray) && !empty($userArray));
assert($userArray['name'] === $userTransfer->name && $userArray['name'] === 'userName');
assert($userArray['age'] === $userTransfer->age && $userArray['age'] === 12);
assert(is_array($userArray['address']));
assert($userArray['address']['city'] === 'someCity');
assert($userArray['address']['country'] === 'someCountry');
assert($userArray['address']['zip'] === 'someZip');
assert($userArray['address']['street'] === 'someStreet');
assert($userArray['address']['streetNumber'] === 12);
/** TEST toArray method - end */


/** TEST modifiedToArray method - end */
$userTransfer = new UserTransfer();

$userTransfer->fromArray([
    'name' => 'userName',
    'address' => [
        'streetNumber' => 12,
    ],
]);
$modifiedProperties = $userTransfer->modifiedToArray();

assert($modifiedProperties['name'] === $userTransfer->name && $userArray['name'] === 'userName');
assert($modifiedProperties['address'] instanceof AddressTransfer);

$modifiedPropertiesR = $userTransfer->modifiedToArray(true);

assert($modifiedPropertiesR['name'] === $userTransfer->name && $userArray['name'] === 'userName');
assert(is_array($modifiedPropertiesR['address']));
assert($modifiedPropertiesR['address']['streetNumber'] === 12);
assert($modifiedPropertiesR['address']['city'] === null);
