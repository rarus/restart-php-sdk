<?php
declare(strict_types=1);

namespace Rarus\Restart\Orders;

/**
 * Class OrderQueryFilter
 * @package Rarus\Restart\Orders
 */
class OrderQueryFilter
{
    /**
     * @var array
     */
    protected $arQuery;
    /**
     * @var string
     */
    protected $serverTimeFormat;
    /**
     * @var string Состояние заказа доставки. Необязательный. “None”, “successfully”, “canceled”
     */
    protected $deliveryStatus;

    /**
     * OrderQueryFilter constructor.
     * @param string $serverTimeFormat
     */
    public function __construct($serverTimeFormat = 'Y.m.d H:i:s.u')
    {
        $this->arQuery = [];
        $this->serverTimeFormat = $serverTimeFormat;
    }

    /**
     * получение фильтра для фильтрации заказов
     *
     * @return string
     */
    public function getQueryFilter(): string
    {
        return http_build_query($this->arQuery);
    }

    /**
     * @param string $orderSourceType
     * @return OrderQueryFilter
     */
    public function setOrderSourceType(string $orderSourceType): OrderQueryFilter
    {
        $this->arQuery['origin'] = $orderSourceType;
        return $this;
    }

    /**
     * Идентификатор заказа. Необязательный
     *
     * @param string $orderId
     * @return OrderQueryFilter
     */
    public function setOrderId(string $orderId): OrderQueryFilter
    {
        $this->arQuery['id'] = $orderId;
        return $this;
    }

    /**
     * Идентификатор дисконтной карты. Необязательный
     *
     * @param string $discountCardId
     * @return OrderQueryFilter
     */
    public function setDiscountCardId(string $discountCardId): OrderQueryFilter
    {
        $this->arQuery['сard_id'] = $discountCardId;
        return $this;
    }

    /**
     * @param string $guestId Идентификатор гостя. Необязательный
     * @return OrderQueryFilter
     */
    public function setGuestId(string $guestId): OrderQueryFilter
    {
        $this->arQuery['guest_id'] = $guestId;
        return $this;
    }

    /**
     * @param string $orderStatus Статус заказа. Необязательный.
     * @return OrderQueryFilter
     */
    public function setOrderStatus(string $orderStatus): OrderQueryFilter
    {
        $this->arQuery['status'] = $orderStatus;
        return $this;
    }

    /**
     * @param \DateTime $dateFrom Дата от. Необязательный.
     * @return OrderQueryFilter
     */
    public function setDateFrom(\DateTime $dateFrom): OrderQueryFilter
    {
        $this->arQuery['date_from'] = $dateFrom->format($this->serverTimeFormat);
        return $this;
    }

    /**
     * @param \DateTime $dateTo Дата до. Необязательный.
     * @return OrderQueryFilter
     */
    public function setDateTo(\DateTime $dateTo): OrderQueryFilter
    {
        $this->arQuery['date_to'] = $dateTo->format($this->serverTimeFormat);
        return $this;
    }

    /**
     * @param bool $isFreeOrder Заказ с не назначенным курьером (true). Необязательный
     * @return OrderQueryFilter
     */
    public function setIsFreeOrder(bool $isFreeOrder): OrderQueryFilter
    {
        $this->arQuery['freeorder'] = $isFreeOrder ? 'true' : 'false';
        return $this;
    }

    /**
     * @param string $courierId Идентификатор курьера. Необязательный
     * @return OrderQueryFilter
     */
    public function setCourierId(string $courierId): OrderQueryFilter
    {
        $this->arQuery['courier_id'] = $courierId;
        return $this;
    }

    /**
     * @param bool $isPaidDelivery
     * @return OrderQueryFilter
     */
    public function setIsPaidDelivery(bool $isPaidDelivery): OrderQueryFilter
    {
        $this->arQuery['ispaid_delivery'] = $isPaidDelivery ? 'true' : 'false';
        return $this;
    }

    /**
     * @param string $deliveryStatus Состояние заказа доставки. Необязательный.
     * @return OrderQueryFilter
     */
    public function setDeliveryStatus(string $deliveryStatus): OrderQueryFilter
    {
        $this->arQuery['delivery_status'] = $deliveryStatus;
        return $this;
    }
}