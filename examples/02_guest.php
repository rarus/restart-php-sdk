<?php

require_once __DIR__ . '/init.php';

use \Rarus\Restart\{
    ApiClient,
    Guests\GuestInterface,
    Guests\GuestManager,
    Guests\Guest
};

// пример со списком гостей
/**
 * @var $apiClient ApiClient
 */
$guestManager = new GuestManager($apiClient, $log);

$obStorage = $guestManager->getList('', 'Екатерина');
print('гости: ' . PHP_EOL);
foreach ($obStorage as $guest) {
    /**
     * @var $guest GuestInterface
     */
    print(sprintf('id: %s' . PHP_EOL, $guest->getId()));
    print(sprintf('parent id: %s' . PHP_EOL, $guest->getParentId()));
    print(sprintf('is group: %s' . PHP_EOL, $guest->isGroup()));
    print(sprintf('name: %s' . PHP_EOL, $guest->getUserInfo()->getName()));
    print(sprintf('first name: %s' . PHP_EOL, $guest->getUserInfo()->getFirstName()));
    print(sprintf('last name: %s' . PHP_EOL, $guest->getUserInfo()->getLastName()));
    print(sprintf('patronymic: %s' . PHP_EOL, $guest->getUserInfo()->getPatronymic()));
    print(sprintf('birthday: %s' . PHP_EOL,
        null === $guest->getUserInfo()->getBirthday() ? 'n/a' : $guest->getUserInfo()->getBirthday()->format(\DateTime::ATOM)));
    print(sprintf('raw delivery address: %s' . PHP_EOL, $guest->getDeliveryAddress()->getRawAddress()));
    print(sprintf('delivery metro: %s' . PHP_EOL, $guest->getDeliveryAddress()->getMetro()));
    print(sprintf('delivery street : %s' . PHP_EOL, $guest->getDeliveryAddress()->getStreet()));
    print(sprintf('delivery apartment : %s' . PHP_EOL, $guest->getDeliveryAddress()->getApartment()));

    //    print(sprintf('actual address: %s' . PHP_EOL, $guest->getActualAddress()));
    print(sprintf('phone 1: %s' . PHP_EOL, $guest->getUserInfo()->getFirstPhone()));
    print(sprintf('phone 2: %s' . PHP_EOL, $guest->getUserInfo()->getSecondPhone()));
    print(sprintf('email: %s' . PHP_EOL, $guest->getUserInfo()->getEmail()));
    print(sprintf('is blacklisted: %s' . PHP_EOL, $guest->isBlacklisted()));
    print(sprintf('blacklisted reason: %s' . PHP_EOL, $guest->getBlacklistedReason()));
    print(sprintf('timestamp: %s' . PHP_EOL, $guest->getTimestamp()->format(\DateTime::ATOM)));
    print('---------------' . PHP_EOL);
}

$obNewUserInfo = \Rarus\Restart\Common\Users\UserInfo::createNewUserInfoItem(
    'Максим Иванович',
    'Максим',
    'Ивашкин',
    'Иванович',
    \DateTime::createFromFormat('Y.m.d H:i:s', '2017.02.16 18:03:49'),
    'testrestart@rarus.ru',
    '79780192059',
    '79780192060'
);
$actualAddress = \Rarus\Restart\Common\Address\AddressInfo::createNewAddressInfoItemFromRawAddress(',,,,,Знаменка,5,2,21,2,3,333,Академическая,');
$deliveryAddress = \Rarus\Restart\Common\Address\AddressInfo::createNewAddressInfoItemFromRawAddress(',,,,,Знаменка Рабочая,5,2,21,2,3,333,Академическая Рабочая,');

$obNewGuest = \Rarus\Restart\Guests\Guest::createNewGuestItem($obNewUserInfo, $actualAddress, $deliveryAddress, '', false, false, '');

$obGuest = $guestManager->add($obNewGuest);
print($obGuest->getId().PHP_EOL);
print($obGuest->getUserInfo()->getName().PHP_EOL);