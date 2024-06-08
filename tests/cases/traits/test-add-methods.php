<?php

use ShveiderDtoTest\DTO\Module2\Transfers\UserTransfer;
use ShveiderDtoTest\DTO\Module3\Transfers\UserCollectionTransfer;

$userCollectionTransfer = new UserCollectionTransfer();
$userCollectionTransfer->addDate(new DateTime());
$userCollectionTransfer->addDate((new DateTime())->modify('+ 2 days'));
$userCollectionTransfer->addOrderData(['order' => 1]);
$userCollectionTransfer->addOrderData(['order' => 2]);
$userCollectionTransfer->addUser((new UserTransfer())->setName('user'));

assert(count($userCollectionTransfer->getDates()) === 2);
assert(count($userCollectionTransfer->getOrdersList()) === 2);
assert(count($userCollectionTransfer->getUsers()) === 1);

$userCollectionTransfer2 = new UserCollectionTransfer();

$userCollectionTransfer2->fromArray(json_decode(
    $userCollectionTransfer->toJson(),
    true
));

assert(!empty($userCollectionTransfer2->getUsers()));
foreach ($userCollectionTransfer2->getUsers() as $user) {
    assert($user->getName() === 'user');
}

assert(!empty($userCollectionTransfer2->getOrdersList()));
foreach ($userCollectionTransfer2->getOrdersList() as $item) {
    assert(is_array($item) && array_key_exists('order', $item));
    assert(in_array($item['order'], [1, 2]));
}

assert(!empty($userCollectionTransfer2->getDates()));
foreach ($userCollectionTransfer2->getDates() as $date) {
    assert(is_a($date, DateTime::class));
}
