<?php
declare(strict_types = 1);

namespace Rarus\Restart\DiscountCards;

/**
 * Class DiscountCard
 * @package Rarus\Restart\DiscountCards
 */
class DiscountCard implements DiscountCardInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string GUID родителя
     */
    protected $parentId;
    /**
     * @var boolean признак группы
     */
    protected $isGroup;

    /**
     * @var string «Данные карты» – строка, необязательный, но если заполнен, то имеет уникальное значение
     * (предназначен для идентификации карты при помощи оборудования)
     */
    protected $code;

    /**
     * @var string «Наименование» – строка, представления карты на формах и в отчетах.
     * Примеры:   Карта питания Иванов П.М.   /    Дисконтная «ЗОЛОТАЯ»
     */
    protected $name;

    /**
     * @var boolean «Заблокирована» - булево, признак временной приостановки действия карты
     * Примечание: при считывании такой карточки выдается соотв. сообщение, но никаких действий в системе не производится
     * (скидка не назначается, оплата не принимается, пользователь не авторизуется и т.д.)
     */
    protected $isBlocked;

    /**
     * @var string Причина блокировки
     */
    protected $blockReason;

    /**
     * @var string «Комментарий»
     */
    protected $comment;

    /**
     * @var string url картинки
     */
    protected $imageUrl;

    /**
     * @var string Гость (Guest.ObjID) при указании позволяет определить гостя по его карточке
     * (например, при ее считывании на оборудовании)
     */
    protected $guestId;

    /**
     * @var \DateTime
     */
    protected $timestamp;

    public static function initFromServerResponse(array $arCard, $serverTimeFormat = 'Y.m.d H:i:s.u'): DiscountCardInterface
    {
        $obCard = new DiscountCard();

        $obCard
            ->setId($arCard['id'])
            ->setParentId($arCard['parent_id'])
            ->setIsGroup((boolean)$arCard['isgroup'])
            ->setCode($arCard['code'])
            ->setName($arCard['name'])
            ->setIsBlocked((boolean)$arCard['blocked'])
            ->setBlockReason($arCard['block_reason'])
            ->setComment($arCard['comment'])
            ->setImageUrl($arCard['image'])
            ->setGuestId($arCard['guest_id'])
            ->setTimestamp(\DateTime::createFromFormat($serverTimeFormat, $arCard['timestamp']));
        return $obCard;
    }

    /**
     * @param string $code
     * @param string $name
     * @param string $guestId
     * @param string $comment
     * @param string $parentId
     * @param bool $isGroup
     * @param bool $isBlocked
     * @param string $blocReason
     * @return DiscountCardInterface
     */
    public static function createNewDiscountCardItem(
        string $code,
        string $name,
        string $guestId,
        string $comment = '',
        string $parentId = '',
        bool $isGroup = false,
        bool $isBlocked = false,
        string $blocReason = ''): DiscountCardInterface
    {
        $obCard = new DiscountCard();

        $obCard
            ->setCode($code)
            ->setName($name)
            ->setGuestId($guestId)
            ->setComment($comment)
            ->setParentId($parentId)
            ->setIsGroup($isGroup)
            ->setIsBlocked($isBlocked)
            ->setBlockReason($blocReason);

        return $obCard;
    }

    /**
     * @param string $id
     * @return DiscountCard
     */
    protected
    function setId(string $id): DiscountCard
    {
        $this->id = $id;
        return $this;
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
     * @return string
     */
    public
    function getParentId(): string
    {
        return $this->parentId;
    }

    /**
     * @param string $parentId
     * @return DiscountCard
     */
    protected
    function setParentId(string $parentId): DiscountCard
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
     * @return DiscountCard
     */
    protected
    function setIsGroup(bool $isGroup): DiscountCard
    {
        $this->isGroup = $isGroup;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return DiscountCard
     */
    protected
    function setCode(string $code): DiscountCard
    {
        $this->code = $code;
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
     * @return DiscountCard
     */
    protected
    function setName(string $name): DiscountCard
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public
    function isBlocked(): bool
    {
        return $this->isBlocked;
    }

    /**
     * @param bool $isBlocked
     * @return DiscountCard
     */
    protected
    function setIsBlocked(bool $isBlocked): DiscountCard
    {
        $this->isBlocked = $isBlocked;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getBlockReason(): string
    {
        return $this->blockReason;
    }

    /**
     * @param string $blockReason
     * @return DiscountCard
     */
    protected
    function setBlockReason(string $blockReason): DiscountCard
    {
        $this->blockReason = $blockReason;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return DiscountCard
     */
    protected
    function setComment(string $comment): DiscountCard
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     * @return DiscountCard
     */
    protected
    function setImageUrl(string $imageUrl): DiscountCard
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    /**
     * @return string
     */
    public
    function getGuestId(): string
    {
        return $this->guestId;
    }

    /**
     * @param string $guestId
     * @return DiscountCard
     */
    protected
    function setGuestId(string $guestId): DiscountCard
    {
        $this->guestId = $guestId;
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
     * @return DiscountCard
     */
    protected
    function setTimestamp(\DateTime $timestamp): DiscountCard
    {
        $this->timestamp = $timestamp;
        return $this;
    }
}