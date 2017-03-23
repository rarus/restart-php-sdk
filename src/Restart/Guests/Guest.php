<?php
declare(strict_types = 1);

namespace Rarus\Restart\Guests;

use Rarus\Restart\Common\Address\AddressInfo;
use Rarus\Restart\Common\Users\UserInfo;

/**
 * Class Guest
 * @package Rarus\Restart\Guests
 */
class Guest implements GuestInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string Родитель, GUID
     */
    protected $parentId;

    /**
     * @var boolean Признак группы, используется для выделения в списке. Все нижеописанные реквизиты не имеют смысла (недоступны) для групп
     */
    protected $isGroup;

    /**
     * @var UserInfo
     */
    protected $userInfo;

    /**
     * @var AddressInfo Основной адрес доставки.
     */
    protected $deliveryAddress;

    /**
     * @var AddressInfo Дополнительный адрес доставки. Список полей, как DelivAddress
     */
    protected $actualAddress;

    /**
     * @var boolean Признак внесения в «чёрный список»
     */
    protected $isBlacklisted;

    /**
     * @var string Причина внесения в «чёрный список»
     */
    protected $blacklistedReason;

    /**
     * @var \DateTime
     */
    protected $timestamp;

    /**
     * @param array $arGuest
     * @param string $serverTimeFormat
     *
     * @return GuestInterface
     */
    public static function initFromServerResponse(array $arGuest, $serverTimeFormat = 'Y.m.d H:i:s.u'): GuestInterface
    {
        $obGuest = new Guest();
        $obGuest
            ->setId($arGuest['id'])
            ->setParentId($arGuest['parent_id'])
            ->setIsGroup((boolean)$arGuest['isgroup'])
            ->setUserInfo(UserInfo::initFromServerResponse($arGuest))
            ->setDeliveryAddress(AddressInfo::createNewAddressInfoItemFromRawAddress($arGuest['delivery_address']))
            ->setActualAddress(AddressInfo::createNewAddressInfoItemFromRawAddress($arGuest['actual_address']))
            ->setIsBlacklisted((boolean)$arGuest['black_listed'])
            ->setBlacklistedReason($arGuest['black_reason'])
            ->setTimestamp(\DateTime::createFromFormat($serverTimeFormat, $arGuest['timestamp']));
        return $obGuest;
    }

    /**
     * @param UserInfo $userInfo
     * @param AddressInfo $deliveryAddress
     * @param AddressInfo $actualAddress
     * @param string $parentId
     * @param bool $isGroup
     * @param bool $isBlacklisted
     * @param string $blacklistedReason
     * @return GuestInterface
     */
    public static function createNewGuestItem(
        UserInfo $userInfo,
        AddressInfo $deliveryAddress,
        AddressInfo $actualAddress,
        string $parentId = '',
        bool $isGroup = false,
        bool $isBlacklisted = false,
        string $blacklistedReason = ''

    ): GuestInterface
    {
        $obGuest = new Guest();

        $obGuest
            ->setUserInfo($userInfo)
            ->setDeliveryAddress($deliveryAddress)
            ->setActualAddress($actualAddress)
            ->setParentId($parentId)
            ->setIsGroup($isGroup)
            ->setIsBlacklisted($isBlacklisted)
            ->setBlacklistedReason($blacklistedReason);
        return $obGuest;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return Guest
     */
    protected function setId(string $id): Guest
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getParentId(): string
    {
        return $this->parentId;
    }

    /**
     * @param string $parentId
     *
     * @return Guest
     */
    protected function setParentId(string $parentId): Guest
    {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isGroup(): bool
    {
        return $this->isGroup;
    }

    /**
     * @param bool $isGroup
     * @return Guest
     */
    protected function setIsGroup(bool $isGroup): Guest
    {
        $this->isGroup = $isGroup;
        return $this;
    }

    /**
     * @return UserInfo
     */
    public function getUserInfo(): UserInfo
    {
        return $this->userInfo;
    }

    /**
     * @param UserInfo $userInfo
     * @return Guest
     */
    protected function setUserInfo(UserInfo $userInfo): Guest
    {
        $this->userInfo = $userInfo;
        return $this;
    }

    /**
     * @return AddressInfo
     */
    public function getDeliveryAddress(): AddressInfo
    {
        return $this->deliveryAddress;
    }

    /**
     * @param AddressInfo $deliveryAddress
     *
     * @return Guest
     */
    protected function setDeliveryAddress(AddressInfo $deliveryAddress): Guest
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }

    /**
     * @return AddressInfo
     */
    public function getActualAddress(): AddressInfo
    {
        return $this->actualAddress;
    }

    /**
     * @param AddressInfo $actualAddress
     *
     * @return Guest
     */
    protected function setActualAddress(AddressInfo $actualAddress): Guest
    {
        $this->actualAddress = $actualAddress;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBlacklisted(): bool
    {
        return $this->isBlacklisted;
    }

    /**
     * @param bool $isBlacklisted
     *
     * @return Guest
     */
    protected function setIsBlacklisted(bool $isBlacklisted): Guest
    {
        $this->isBlacklisted = $isBlacklisted;
        return $this;
    }

    /**
     * @return string
     */
    public function getBlacklistedReason(): string
    {
        return $this->blacklistedReason;
    }

    /**
     * @param string $blacklistedReason
     *
     * @return Guest
     */
    protected function setBlacklistedReason(string $blacklistedReason): Guest
    {
        $this->blacklistedReason = $blacklistedReason;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     *
     * @return Guest
     */
    protected function setTimestamp(\DateTime $timestamp): Guest
    {
        $this->timestamp = $timestamp;
        return $this;
    }
}