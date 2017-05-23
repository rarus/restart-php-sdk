<?php
declare(strict_types=1);

namespace Rarus\Restart\Orders;

use Rarus\Restart\Common\{
    Products\Product,
    Discounts\Discount
};

/**
 * строка заказа
 *
 * Class OrderItem
 * @package Rarus\Restart\Orders
 */
class OrderRowItem
{
    /**
     * @var string Уникальный ID строки. Ссылки на него возможны только из этой же табличной части.
     */
    public $id;
    /**
     * @var string Родитель (OrdItem.ID). Для товаров – пусто. Для модификаторов – ссылка на строку с  товаром.
     */
    public $parentId;
    /**
     * @var \DateTime Дата/Время добавления строки
     */
    public $dateAdd;
    /**
     * @var \DateTime Дата/Время удаления позиции.
     */
    public $dateDelete;
    /**
     * @var string Инициатор удаления позиции
     */
    public $responsibleUserName;
    /**
     * @var string Описание отмены
     */
    public $cancelDescription;
    /**
     * @var Product
     */
    public $product;
    /**
     * @var string Модификатор (Mod.ObjID). Заполнено только у модификаторной строки.
     */
    public $modifierId;
    /**
     * @var string Меню (Menu.ObjID).
     */
    public $menuId;
    /**
     * @var string Полная сумма строки. TotalSum = Sum - TotalDiscSum
     */
    public $totalSum;
    /**
     * @var Discount
     */
    public $discount;
    /**
     * @var Discount
     */
    public $totalDiscount;
    /**
     * @var string Статус строки.
     *  1 – новая (неотпечатанная) (по-умолчанию)
     *  2 – отпечатана
     *  3 – отменена
     *  4 – принята в готовку
     *  5 – приготовлено
     *  6 – подано (клиенту)
     *  7 – ожидание принятия в готовку
     */
    public $status;
    /**
     * @var string Комментарий
     */
    public $comment;

    /**
     * OrderRowItem constructor.
     */
    public function __construct()
    {
        $this->comment = '';
    }

    /**
     * @param array $arOrderItem
     * @param string $serverTimeFormat
     * @return OrderRowItem
     */
    public static function initFromServerResponse(array $arOrderItem, $serverTimeFormat = 'Y.m.d H:i:s.u'): OrderRowItem
    {
        $obOrderItem = new OrderRowItem();

        $obOrderItem
            ->setId($arOrderItem['id'])
            ->setParentId($arOrderItem['parent_id'])
            ->setDateAdd(\DateTime::createFromFormat($serverTimeFormat, $arOrderItem['date_add']))
            ->setDateDelete(\DateTime::createFromFormat($serverTimeFormat, $arOrderItem['date_del']))
            ->setResponsibleUserName($arOrderItem['user_del_name'])
            ->setCancelDescription($arOrderItem['cancel_description'])
            ->setProduct(new Product(
                $arOrderItem['product_id'],
                $arOrderItem['product_name'],
                $arOrderItem['product_shortname'],
                (string)$arOrderItem['price'],
                (int)$arOrderItem['count'],
                (string)$arOrderItem['sum']
            ))
            ->setModifierId($arOrderItem['modifier_id'])
            ->setMenuId($arOrderItem['menu_id'])
            ->setStatus($arOrderItem['status'])
            ->setComment($arOrderItem['comment'])
            ->setTotalSum((string)$arOrderItem['total_sum'])
            ->setDiscount(new Discount(
                (string)$arOrderItem['discount_percent'],
                (string)$arOrderItem['discount_sum']
            ))
            ->setTotalDiscount(new Discount(
                (string)$arOrderItem['total_discount_percent'],
                (string)$arOrderItem['total_discount_sum']
            ));

        return $obOrderItem;
    }

    /**
     * получение структуры данных для добавления новой строки табличной части заказа
     * @return array
     */
    public function getDataForNewItem(): array
    {
        return [
            'product_id' => $this->getProduct()->getId(),
            'price' => $this->getProduct()->getPrice(),
            'count' => $this->getProduct()->getCount(),
            'sum' => $this->getProduct()->getSum(),
            'comment' => $this->getComment(),
            'menu_id' => $this->getMenuId(),
        ];
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return OrderRowItem
     */
    public function setProduct(Product $product): OrderRowItem
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return OrderRowItem
     */
    public function setComment(string $comment): OrderRowItem
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string
     */
    public function getMenuId(): string
    {
        return $this->menuId;
    }

    /**
     * @param string $menuId
     * @return OrderRowItem
     */
    public function setMenuId(string $menuId): OrderRowItem
    {
        $this->menuId = $menuId;
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
     * @return OrderRowItem
     */
    public function setId(string $id): OrderRowItem
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
     * @return OrderRowItem
     */
    public function setParentId(string $parentId): OrderRowItem
    {
        $this->parentId = $parentId;
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
     * @return OrderRowItem
     */
    public function setDateAdd(\DateTime $dateAdd): OrderRowItem
    {
        $this->dateAdd = $dateAdd;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateDelete(): \DateTime
    {
        return $this->dateDelete;
    }

    /**
     * @param \DateTime $dateDelete
     * @return OrderRowItem
     */
    public function setDateDelete($dateDelete = null): OrderRowItem
    {
        $this->dateDelete = $dateDelete;
        return $this;
    }

    /**
     * @return string
     */
    public function getResponsibleUserName(): string
    {
        return $this->responsibleUserName;
    }

    /**
     * @param string $responsibleUserName
     * @return OrderRowItem
     */
    public function setResponsibleUserName(string $responsibleUserName): OrderRowItem
    {
        $this->responsibleUserName = $responsibleUserName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCancelDescription(): string
    {
        return $this->cancelDescription;
    }

    /**
     * @param string $cancelDescription
     * @return OrderRowItem
     */
    public function setCancelDescription(string $cancelDescription): OrderRowItem
    {
        $this->cancelDescription = $cancelDescription;
        return $this;
    }

    /**
     * @return string
     */
    public function getModifierId(): string
    {
        return $this->modifierId;
    }

    /**
     * @param string $modifierId
     * @return OrderRowItem
     */
    public function setModifierId(string $modifierId): OrderRowItem
    {
        $this->modifierId = $modifierId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTotalSum(): string
    {
        return $this->totalSum;
    }

    /**
     * @param string $totalSum
     * @return OrderRowItem
     */
    public function setTotalSum(string $totalSum): OrderRowItem
    {
        $this->totalSum = $totalSum;
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
     * @return OrderRowItem
     */
    public function setDiscount(Discount $discount): OrderRowItem
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @return Discount
     */
    public function getTotalDiscount(): Discount
    {
        return $this->totalDiscount;
    }

    /**
     * @param Discount $totalDiscount
     * @return OrderRowItem
     */
    public function setTotalDiscount(Discount $totalDiscount): OrderRowItem
    {
        $this->totalDiscount = $totalDiscount;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return OrderRowItem
     */
    public function setStatus(string $status): OrderRowItem
    {
        $this->status = $status;
        return $this;
    }
}