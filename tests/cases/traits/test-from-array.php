<?php

use ShveiderDtoTest\DTO\Module2\Transfers\AddressTransfer;
use ShveiderDtoTest\DTO\Module2\Transfers\UserAddRequestTransfer;
use ShveiderDtoTest\DTO\Module2\Transfers\UserTransfer;
use ShveiderDtoTest\DTO\Module3\Transfers\UserCollectionTransfer;

$checkAddRequestTransfer = function (UserAddRequestTransfer $userAddRequestTransfer) {
    assert(is_a($userAddRequestTransfer->getUser(), UserTransfer::class));
    $userTransfer = $userAddRequestTransfer->getUser();

    assert(
        $userTransfer->getName() === 'Stepan' &&
        $userTransfer->getAge() === 100 &&
        is_a($userTransfer->getAddress(), AddressTransfer::class),
    );

    $addressTransfer = $userTransfer->getAddress();

    assert(
        $addressTransfer->getCity() === 'Odessa' &&
        $addressTransfer->getCountry() === 'Ukraine' &&
        $addressTransfer->getZip() === '30643' &&
        $addressTransfer->getStreet() === 'some Street' &&
        $addressTransfer->getStreetNumber() === 12
    );
};

$userAddRequestTransfer = new UserAddRequestTransfer();
$userAddRequestTransfer->fromArray([
    'user' => [
        'name' => 'Stepan',
        'age' => 100,
        'address' => [
            'city' => 'Odessa',
            'country' => 'Ukraine',
            'zip' => '30643',
            'street' => 'some Street',
            'streetNumber' => 12,
        ],
    ],
]);
$checkAddRequestTransfer($userAddRequestTransfer);

$userAddRequestTransfer2 = new UserAddRequestTransfer();
$userAddRequestTransfer2->fromArray($userAddRequestTransfer->toArray(true));
$checkAddRequestTransfer($userAddRequestTransfer2);

$userAddRequestTransfer3 = new UserAddRequestTransfer();
$userAddRequestTransfer3->fromArray($userAddRequestTransfer2->toArray());
$checkAddRequestTransfer($userAddRequestTransfer3);

$userCollectionTransfer = new UserCollectionTransfer();
$userCollectionTransfer->fromArray([
    'users' => [
        (new UserTransfer())->fromArray([
            'name' => 'Stepan',
            'age' => 23,
        ]),
        (new UserTransfer())->fromArray([
            'name' => 'Stepan',
            'age' => 23,
        ]),
    ],
]);

foreach ($userCollectionTransfer->getUsers() as $user) {
    assert($user->getName() === 'Stepan');
    assert($user->getAge() === 23);
}

$userCollectionTransfer2 = new UserCollectionTransfer();
$userCollectionTransfer2->fromArray($userCollectionTransfer->toArray(true));

foreach ($userCollectionTransfer2->getUsers() as $user) {
    assert($user->getName() === 'Stepan');
    assert($user->getAge() === 23);
}
