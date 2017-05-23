<?php
declare(strict_types=1);

namespace Rarus\Restart\Orders;

use Rarus\Restart\Common\{
    Address\AddressInfo, DeliveryInfo\DeliveryInfo, Users\UserInfo, Discounts\Discount
};

/**
 * Class Order
 * @package Rarus\Restart\Orders
 */
class Order
{
    /**
     * @var string
     */
    const STATUS_OPEN = 'open';
    /**
     * @var string
     */
    const STATUS_CLOSE = 'close';
    /**
     * @var string
     */
    const STATUS_PRECHECK = 'precheck';
    /**
     * @var string
     */
    const STATUS_RESERVATIONS = 'reservations';
    /**
     * @var string
     */
    const STATUS_RECEIVED_DELIVERY = 'receiveddelivery';
    /**
     * @var string
     */
    const STATUS_SENDING_DELIVERY = 'sendingdelivery';
    /**
     * @var string
     */
    const ORIGIN_WAITER = 'waiter';
    /**
     * @var string
     */
    protected $id;
    /**
     * @var int Номер заказа. Уникальный для БД.
     */
    protected $num;
    /**
     * @var \DateTime Дата/Время создания.
     */
    protected $dateAdd;
    /**
     * @var \DateTime Дата/Время последнего изменения
     */
    protected $dateChange;
    /**
     * @var \DateTime Дата/Время пробития пречека
     */
    protected $datePrecheck;
    /**
     * @var \DateTime Дата/время готовности заказа доставки
     */
    protected $dateReady;
    /**
     * @var \DateTime Дата/время, на которую забронирован стол.
     */
    protected $dateReservation;
    /**
     * @var \DateTime
     */
    protected $timestamp;
    /**
     * @var string Префикс кассового узла (как в файлах обмена)
     */
    protected $prefixDb;
    /**
     * @var UserInfo
     */
    protected $user;
    /**
     * @var string Код карты, на которую зачислен аванс
     */
    protected $prepaymentCard;
    /**
     * @var float Сумма аванса
     */
    protected $prepaymentSum;
    /**
     * @var string Стол заказа (Object.ObjID).
     */
    protected $tableId;
    /**
     * @var int Число гостей, обслуживаемых по текущему заказу
     */
    protected $seats;
    /**
     * @var string Статус заказа
     *    1 – открыт
     *  2 – закрыт (отменён / перенесён / пробит)
     *  3 – пречек
     *  4 – предварительный (бронь)
     *  10 – принят заказ доставки
     *  11 – отправлен заказ доставки
     */
    protected $orderStatus;
    /**
     * @var string Происхождение заказа (АРМ, в котором создали):
     *  1 – продавец (ФФ)
     *  2 – официант (“waiter”)
     *  3 – доставка
     *  5 – самообслуживание
     */
    protected $origin;
    /**
     * @var int Метка заказа (номер бирки посетителя)
     */
    protected $label;
    /**
     * @var string Дисконтная карта (Card.ObjID).
     */
    protected $discountCardId;
    /**
     * @var Discount
     */
    protected $discount;
    /**
     * @var string  Гость (Guest.ObjID). В «Официанте» заполняется по карточке. В «Доставке» – клиент.
     */
    protected $guestId;
    /**
     * @var bool Оплаченность заказа доставки. 0 при закрытом заказе, если реальная оплата – после чека продажи
     */
    protected $isPaid;
    /**
     * @var \SplObjectStorage
     */
    protected $rowItems;
    /**
     * @var DeliveryInfo
     */
    protected $deliveryInfo;
    /**
     * @var string
     */
    protected $serverTimeFormat;

    /**
     * Order constructor.
     * @param string $serverTimeFormat
     */
    public function __construct(string $serverTimeFormat = 'Y.m.d H:i:s.u')
    {
        $this->serverTimeFormat = $serverTimeFormat;
    }

    /**
     * @param array $arOrder
     * @param string $serverTimeFormat
     * @return Order
     */
    public static function initFromServerResponse(array $arOrder, $serverTimeFormat = 'Y.m.d H:i:s.u'): Order
    {
        $obOrder = new Order($serverTimeFormat);

        $obOrder
            ->setId($arOrder['id'])
            ->setNum($arOrder['num'])
            ->setDateAdd(\DateTime::createFromFormat($serverTimeFormat, $arOrder['date_add']))
            ->setDatePrecheck($arOrder['date_precheck'] !== '' ? \DateTime::createFromFormat($serverTimeFormat, $arOrder['date_precheck']) : null)
            ->setDateReservation($arOrder['date_reservation'] !== '' ? \DateTime::createFromFormat($serverTimeFormat, $arOrder['date_reservation']) : null)
            ->setTimestamp(\DateTime::createFromFormat($serverTimeFormat, $arOrder['timestamp']))
            ->setPrefixDb($arOrder['prefix_db'])
            ->setUser(UserInfo::createNewUserInfoItem(
                $arOrder['fio_reservation'],
                '',
                '',
                '',
                null,
                '',
                $arOrder['info_reservation']
            ))
            ->setPrepaymentCard($arOrder['prepayment_card'])
            ->setPrepaymentSum($arOrder['prepayment_sum'])
            ->setTableId($arOrder['object_id'])
            ->setSeats((int)$arOrder['seats'])
            ->setOrderStatus($arOrder['status'])
            ->setOrigin($arOrder['origin'])
            ->setLabel($arOrder['label'])
            ->setDiscountCardId($arOrder['card_id'])
            ->setDiscount(new Discount((string)$arOrder['discount_percent'], (string)$arOrder['discount_sum']))
            ->setGuestId($arOrder['guest_id'])
            ->setIsPaid($arOrder['ispaid_delivery'])
            ->setDeliveryInfo(new DeliveryInfo(
                UserInfo::createNewUserInfoItem(
                    $arOrder['delivery_first_name'] . ' ' . $arOrder['delivery_last_name'],
                    $arOrder['delivery_first_name'],
                    $arOrder['delivery_last_name'],
                    $arOrder['delivery_patronymic'],
                    null,
                    '',
                    $arOrder['delivery_phone'],
                    $arOrder['delivery_phone2']),
                AddressInfo::createNewAddressInfoItemFromRawAddress($arOrder['delivery_address']),
                \DateTime::createFromFormat($serverTimeFormat, $arOrder['date_delivery']),
                $arOrder['delivery_zone_id'],
                (float)$arOrder['delivery_client_sum'],
                $arOrder['delivery_info']
            ));

        // есть табличная часть заказа?
        if (array_key_exists('items', $arOrder)) {
            $orderItems = new \SplObjectStorage();
            foreach ((array)$arOrder['items'] as $orderItem) {
                $orderItems->attach(OrderRowItem::initFromServerResponse($orderItem, $serverTimeFormat));
            }
            $obOrder->setRowItems($orderItems);
        }
        return $obOrder;
    }

    /**
     * @param bool $isPaid
     * @return Order
     */
    public function setIsPaid(bool $isPaid): Order
    {
        $this->isPaid = $isPaid;
        return $this;
    }

    /**
     * получение структуры данных в виде массива для передачи на сервер
     * @return array
     */
    public function getDataAsArray(): array
    {
        $rowItems = [];
        foreach ($this->getRowItems() as $rowItem) {
            /**
             * @var $rowItem OrderRowItem
             */
            $rowItems[] = $rowItem->getDataForNewItem();
        }

        return [
            'date_reservation' => $this->getDateReservation()->format($this->serverTimeFormat),
            'fio_reservation' => $this->getUser()->getName(),
            'info_reservation' => $this->getUser()->getFirstPhone(),
            'object_id' => $this->getTableId(),
            'seats' => $this->getSeats(),
            'status' => $this->getOrderStatus(),
            'origin' => $this->getOrigin(),
            'card_id' => $this->getDiscountCardId(),
            'guest_id' => $this->getGuestId(),
//            'items' => $rowItems
        ];
    }

    /**
     * @return \SplObjectStorage
     */
    public function getRowItems(): \SplObjectStorage
    {
        return $this->rowItems;
    }

    /**
     * @param \SplObjectStorage $rowItems
     * @return Order
     */
    public function setRowItems(\SplObjectStorage $rowItems): Order
    {
        $this->rowItems = $rowItems;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateReservation(): \DateTime
    {
        return $this->dateReservation;
    }

    /**
     * @param \DateTime $dateReservation
     * @return Order
     */
    public function setDateReservation($dateReservation): Order
    {
        $this->dateReservation = $dateReservation;
        return $this;
    }

    /**
     * @return UserInfo
     */
    public function getUser(): UserInfo
    {
        return $this->user;
    }

    /**
     * @param UserInfo $user
     * @return Order
     */
    public function setUser(UserInfo $user): Order
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getTableId(): string
    {
        return $this->tableId;
    }

    /**
     * @param string $tableId
     * @return Order
     */
    public function setTableId(string $tableId): Order
    {
        $this->tableId = $tableId;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeats(): int
    {
        return $this->seats;
    }

    /**
     * @param int $seats
     * @return Order
     */
    public function setSeats(int $seats): Order
    {
        $this->seats = $seats;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderStatus(): string
    {
        return $this->orderStatus;
    }

    /**
     * @param string $orderStatus
     * @return Order
     */
    public function setOrderStatus(string $orderStatus): Order
    {
        $this->orderStatus = $orderStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     * @return Order
     */
    public function setOrigin(string $origin): Order
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @return string
     */
    public function getDiscountCardId(): string
    {
        return $this->discountCardId;
    }

    /**
     * @param string $discountCardId
     * @return Order
     */
    public function setDiscountCardId(string $discountCardId): Order
    {
        $this->discountCardId = $discountCardId;
        return $this;
    }

    /**
     * @return string
     */
    public function getGuestId(): string
    {
        return $this->guestId;
    }

    /**
     * @param string $guestId
     * @return Order
     */
    public function setGuestId(string $guestId): Order
    {
        $this->guestId = $guestId;
        return $this;
    }

    /**
     * @return int
     */
    public function getNum(): int
    {
        return $this->num;
    }

    /**
     * @param int $num
     * @return Order
     */
    public function setNum(int $num): Order
    {
        $this->num = $num;
        return $this;
    }

    /**
     * получение информации по доставке
     *
     * @return DeliveryInfo
     */
    public function getDeliveryInfo(): DeliveryInfo
    {
        return $this->deliveryInfo;
    }

    /**
     * сохранение информации по доставке
     *
     * @param DeliveryInfo $deliveryInfo
     * @return Order
     */
    public function setDeliveryInfo(DeliveryInfo $deliveryInfo): Order
    {
        $this->deliveryInfo = $deliveryInfo;
        return $this;
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
     * @return Order
     */
    public function setId(string $id): Order
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdd(): \DateTime
    {
        return $this->dateAdd;
    }

    /**
     * @param \DateTime $dateAdd
     * @return Order
     */
    public function setDateAdd(\DateTime $dateAdd): Order
    {
        $this->dateAdd = $dateAdd;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateChange(): \DateTime
    {
        return $this->dateChange;
    }

    /**
     * @param \DateTime $dateChange
     * @return Order
     */
    public function setDateChange(\DateTime $dateChange): Order
    {
        $this->dateChange = $dateChange;
        return $this;
    }

    /**
     * @return \DateTime | null
     */
    public function getDatePrecheck()
    {
        return $this->datePrecheck;
    }

    /**
     * @param \DateTime $datePrecheck
     * @return Order
     */
    public function setDatePrecheck($datePrecheck): Order
    {
        $this->datePrecheck = $datePrecheck;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateReady(): \DateTime
    {
        return $this->dateReady;
    }

    /**
     * @param \DateTime $dateReady
     * @return Order
     */
    public function setDateReady(\DateTime $dateReady): Order
    {
        $this->dateReady = $dateReady;
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
     * @return Order
     */
    public function setTimestamp(\DateTime $timestamp): Order
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrefixDb(): string
    {
        return $this->prefixDb;
    }

    /**
     * @param string $prefixDb
     * @return Order
     */
    public function setPrefixDb(string $prefixDb): Order
    {
        $this->prefixDb = $prefixDb;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrepaymentCard(): string
    {
        return $this->prepaymentCard;
    }

    /**
     * @param string $prepaymentCard
     * @return Order
     */
    public function setPrepaymentCard(string $prepaymentCard): Order
    {
        $this->prepaymentCard = $prepaymentCard;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrepaymentSum(): float
    {
        return $this->prepaymentSum;
    }

    /**
     * @param float $prepaymentSum
     * @return Order
     */
    public function setPrepaymentSum(float $prepaymentSum): Order
    {
        $this->prepaymentSum = $prepaymentSum;
        return $this;
    }

    /**
     * @return int
     */
    public function getLabel(): int
    {
        return $this->label;
    }

    /**
     * @param int $label
     * @return Order
     */
    public function setLabel(int $label): Order
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return Discount
     */
    public function getDiscount(): Discount
    {
        return $this->discount;
    }

    /**
     * @param Discount $discount
     * @return Order
     */
    public function setDiscount(Discount $discount): Order
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->isPaid;
    }
}