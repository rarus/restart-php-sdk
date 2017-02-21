<?php
declare(strict_types = 1);

namespace Rarus\Restart\Guests;

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
     * @var string Наименование, представление на формах и в отчетах
     */
    protected $name;

    /**
     * @var string Имя
     */
    protected $firstName;

    /**
     * @var string Фамилия
     */
    protected $lastName;
    /**
     * @var string Отчество
     */
    protected $patronymic;

    /**
     * @var \DateTime Дата рождения
     */
    protected $birthday;

    /**
     * @var string Основной адрес доставки.
     *
     * Список полей (через «,»):
     * - Индекс
     * - Регион
     * - Район
     * - Город
     * - Населенный пункт
     * - Улица
     * - Дом
     * - Корпус
     * - Квартира
     * - Подъезд
     * - Этаж
     * - Код двери/домофон
     * - Станция метро
     * - Городской район (зона)
     */
    protected $deliveryAddress;

    /**
     * @var string Дополнительный адрес доставки. Список полей, как DelivAddress
     */
    protected $actualAddress;

    /**
     * @var string Станция метро из $deliveryAddress
     */
    protected $deliveryMetro;

    /**
     * @var string Улица из $deliveryAddress
     */
    protected $deliveryStreet;

    /**
     * @var string Зона из $deliveryAddress
     */
    protected $deliveryDistrict;

    /**
     * @var string Контактный телефон
     */
    protected $firstPhone;

    /**
     * @var string Дополнительный телефон
     */
    protected $secondPhone;

    /**
     * @var string Адрес e-mail
     */
    protected $email;

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
    public static function initFromServerResponse(
        array $arGuest,
        $serverTimeFormat = 'Y.m.d H:i:s.u'
    ): GuestInterface
    {

        $obGuest = new Guest();

        if ($arGuest['date_birth'] !== '') {
            $obGuest->setBirthday(\DateTime::createFromFormat($serverTimeFormat, $arGuest['date_birth']));
        }

        $obGuest
            ->setId($arGuest['id'])
            ->setParentId($arGuest['parent_id'])
            ->setIsGroup((boolean)$arGuest['isgroup'])
            ->setName($arGuest['name'])
            ->setFirstName($arGuest['first_name'])
            ->setLastName($arGuest['last_name'])
            ->setPatronymic($arGuest['patronymic'])
            ->setDeliveryAddress($arGuest['delivery_address'])
            ->setActualAddress($arGuest['actual_address'])
            ->setDeliveryMetro($arGuest['delivery_metro'])
            ->setDeliveryStreet($arGuest['delivery_street'])
            ->setDeliveryDistrict($arGuest['delivery_distr'])
            ->setFirstPhone($arGuest['phone1'])
            ->setSecondPhone($arGuest['phone2'])
            ->setEmail($arGuest['email'])
            ->setIsBlacklisted((boolean)$arGuest['black_listed'])
            ->setBlacklistedReason($arGuest['black_reason'])
            ->setTimestamp(\DateTime::createFromFormat($serverTimeFormat, $arGuest['timestamp']));
        return $obGuest;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $patronymic
     * @param null $birthday
     * @param string $email
     * @param string $firstPhone
     * @param string $secondPhone
     * @param string $actualAddress
     * @param string $deliveryAddress
     * @param string $parentId
     * @return GuestInterface
     */
    public static function createNewGuestItem(
        string $firstName = '',
        string $lastName = '',
        string $patronymic = '',
        $birthday = null,
        string $email = '',
        string $firstPhone = '',
        string $secondPhone = '',
        string $actualAddress = ',,,,,,,,,,,,,',
        string $deliveryAddress = ',,,,,,,,,,,,,',
        string $parentId = ''
    ): GuestInterface
    {
        $obGuest = new Guest();

        if ($birthday instanceof \DateTime) {
            $obGuest->setBirthday($birthday);
        }

        $obGuest
            ->setIsGroup(false)
            ->setParentId($parentId)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPatronymic($patronymic)
            ->setDeliveryAddress($deliveryAddress)
            ->setActualAddress($actualAddress)
            ->setFirstPhone($firstPhone)
            ->setSecondPhone($secondPhone)
            ->setEmail($email);

        return $obGuest;
    }

    /**
     * @return string
     */
    public
    function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return Guest
     */
    protected
    function setId(string $id): Guest
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getParentId(): string
    {
        return $this->parentId;
    }

    /**
     * @param string $parentId
     *
     * @return Guest
     */
    protected
    function setParentId(string $parentId): Guest
    {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * @return bool
     */
    public
    function isGroup(): bool
    {
        return $this->isGroup;
    }

    /**
     * @param bool $isGroup
     *
     * @return Guest
     */
    protected
    function setIsGroup(bool $isGroup): Guest
    {
        $this->isGroup = $isGroup;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Guest
     */
    protected
    function setName(string $name): Guest
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return Guest
     */
    protected
    function setFirstName(string $firstName): Guest
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return Guest
     */
    protected
    function setLastName(string $lastName): Guest
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getPatronymic(): string
    {
        return $this->patronymic;
    }

    /**
     * @param string $patronymic
     *
     * @return Guest
     */
    protected
    function setPatronymic(string $patronymic): Guest
    {
        $this->patronymic = $patronymic;
        return $this;
    }

    /**
     * @return \DateTime | null
     */
    public
    function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     *
     * @return Guest
     */
    protected
    function setBirthday(\DateTime $birthday): Guest
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getDeliveryAddress(): string
    {
        return $this->deliveryAddress;
    }

    /**
     * @param string $deliveryAddress
     *
     * @return Guest
     */
    protected
    function setDeliveryAddress(string $deliveryAddress): Guest
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getActualAddress(): string
    {
        return $this->actualAddress;
    }

    /**
     * @param string $actualAddress
     *
     * @return Guest
     */
    protected
    function setActualAddress(string $actualAddress): Guest
    {
        $this->actualAddress = $actualAddress;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getDeliveryMetro(): string
    {
        return $this->deliveryMetro;
    }

    /**
     * @param string $deliveryMetro
     *
     * @return Guest
     */
    protected
    function setDeliveryMetro(string $deliveryMetro): Guest
    {
        $this->deliveryMetro = $deliveryMetro;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getDeliveryStreet(): string
    {
        return $this->deliveryStreet;
    }

    /**
     * @param string $deliveryStreet
     *
     * @return Guest
     */
    protected
    function setDeliveryStreet(string $deliveryStreet): Guest
    {
        $this->deliveryStreet = $deliveryStreet;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getDeliveryDistrict(): string
    {
        return $this->deliveryDistrict;
    }

    /**
     * @param string $deliveryDistrict
     *
     * @return Guest
     */
    protected
    function setDeliveryDistrict(string $deliveryDistrict): Guest
    {
        $this->deliveryDistrict = $deliveryDistrict;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getFirstPhone(): string
    {
        return $this->firstPhone;
    }

    /**
     * @param string $firstPhone
     *
     * @return Guest
     */
    protected
    function setFirstPhone(string $firstPhone): Guest
    {
        $this->firstPhone = $firstPhone;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getSecondPhone(): string
    {
        return $this->secondPhone;
    }

    /**
     * @param string $secondPhone
     *
     * @return Guest
     */
    protected
    function setSecondPhone(string $secondPhone): Guest
    {
        $this->secondPhone = $secondPhone;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Guest
     */
    protected
    function setEmail(string $email): Guest
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return bool
     */
    public
    function isBlacklisted(): bool
    {
        return $this->isBlacklisted;
    }

    /**
     * @param bool $isBlacklisted
     *
     * @return Guest
     */
    protected
    function setIsBlacklisted(bool $isBlacklisted): Guest
    {
        $this->isBlacklisted = $isBlacklisted;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getBlacklistedReason(): string
    {
        return $this->blacklistedReason;
    }

    /**
     * @param string $blacklistedReason
     *
     * @return Guest
     */
    protected
    function setBlacklistedReason(string $blacklistedReason): Guest
    {
        $this->blacklistedReason = $blacklistedReason;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public
    function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     *
     * @return Guest
     */
    protected
    function setTimestamp(\DateTime $timestamp): Guest
    {
        $this->timestamp = $timestamp;
        return $this;
    }
}