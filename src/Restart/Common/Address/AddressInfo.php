<?php
declare(strict_types = 1);

namespace Rarus\Restart\Common\Address;

/**
 * Class AddressInfo
 * @package Rarus\Restart\Common\Address
 */
class AddressInfo
{
    /**
     * @var string адрес доставки.
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
    protected $rawAddress;

    /**
     * @var array адрес доставки, для простоты используется массив с ключами
     *  - index индекс улицы в адресе, если есть
     *  - region регион
     *  - district район
     *  - city город
     *  - populated_area населённый пункт
     *  - street улица
     *  - home дом
     *  - housing корпус
     *  - apartment квартира
     *  - entrance подъезд
     *  - floor этаж
     *  - doorphone домофон
     *  - metro метро
     *  - region_id городской район (зона)
     */
    protected $address;

    /**
     * @param string $rawAddress
     * @return AddressInfo
     */
    protected function setRawAddress(string $rawAddress): AddressInfo
    {
        $this->rawAddress = $rawAddress;
        $arAddress = explode(',', $rawAddress);
        $this->address = [
            'index' => $arAddress[0],
            'region' => $arAddress[1],
            'district' => $arAddress[2],
            'city' => $arAddress[3],
            'populated_area' => $arAddress[4],
            'street' => $arAddress[5],
            'home' => $arAddress[6],
            'housing' => $arAddress[7],
            'apartment' => $arAddress[8],
            'entrance' => $arAddress[9],
            'floor' => $arAddress[10],
            'doorphone' => $arAddress[11],
            'metro' => $arAddress[12],
            'region_id' => $arAddress[13]
        ];
        return $this;
    }

    /**
     * @return string
     */
    public function getRawAddress(): string
    {
        return $this->rawAddress;
    }

    /**
     * @param string $rawAddress
     * @return AddressInfo
     * todo перевести на конструктор
     */
    public static function createNewAddressInfoItemFromRawAddress(string $rawAddress): AddressInfo
    {
        $obNewAddressInfo = new AddressInfo();
        $obNewAddressInfo->setRawAddress($rawAddress);
        return $obNewAddressInfo;
    }

    /**
     * @return null|string
     */
    public function getMetro()
    {
        return $this->address['metro'];
    }

    /**
     * @return null | string
     */
    public function getStreet()
    {
        return $this->address['street'];
    }

    /**
     * @return null | string
     */
    public function getHome()
    {
        return $this->address['home'];
    }

    /**
     * @return null | string
     */
    public function getApartment()
    {
        return $this->address['apartment'];
    }

    /**
     * @return null
     */
    public function getDistrict()
    {
        return $this->address['district'];
    }
}