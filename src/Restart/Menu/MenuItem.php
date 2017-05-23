<?php
declare(strict_types=1);

namespace Rarus\Restart\Menu;

/**
 * Class MenuItem
 * @package Rarus\Restart\Menu
 */
class MenuItem
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $parentId;

    /**
     * @var boolean
     */
    protected $isStopped;

    /**
     * @var boolean
     */
    protected $isGroup;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var double
     */
    protected $price;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var string
     */
    protected $productId;

    /**
     * @param array $arMenuItem
     *
     * @return MenuItem
     */
    public static function initFromServerResponse(array $arMenuItem): MenuItem
    {
        $obMenuItem = new MenuItem();
        $obMenuItem
            ->setId((string)$arMenuItem['id'])
            ->setProductId($arMenuItem['product_id'])
            ->setParentId((string)$arMenuItem['parent_id'])
            ->setIsStopped((bool)$arMenuItem['stopped'])
            ->setIsGroup((bool)$arMenuItem['isgroup'])
            ->setName((string)$arMenuItem['name'])
            ->setPosition((int)$arMenuItem['position'])
            ->setImage((string)$arMenuItem['image'])
            ->setPrice((float)$arMenuItem['price'])
            ->setComment((string)$arMenuItem['comment']);

        return $obMenuItem;
    }

    /**
     * @param bool $isGroup
     *
     * @return MenuItem
     */
    protected function setIsGroup(bool $isGroup): MenuItem
    {
        $this->isGroup = $isGroup;
        return $this;
    }

    /**
     * @param bool $isStopped
     *
     * @return MenuItem
     */
    protected function setIsStopped(bool $isStopped): MenuItem
    {
        $this->isStopped = $isStopped;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @param string $productId
     * @return MenuItem
     */
    protected function setProductId(string $productId): MenuItem
    {
        $this->productId = $productId;
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
     *
     * @return MenuItem
     */
    protected function setId(string $id): MenuItem
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
     * @return MenuItem
     */
    protected function setParentId(string $parentId): MenuItem
    {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStopped(): bool
    {
        return $this->isStopped;
    }

    /**
     * @return bool
     */
    public function isGroup(): bool
    {
        return $this->isGroup;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return MenuItem
     */
    protected function setName(string $name): MenuItem
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     *
     * @return MenuItem
     */
    protected function setPosition(int $position): MenuItem
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return MenuItem
     */
    protected function setImage(string $image): MenuItem
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return double
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return MenuItem
     */
    protected function setPrice(float $price): MenuItem
    {
        $this->price = $price;
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
     *
     * @return MenuItem
     */
    protected function setComment(string $comment): MenuItem
    {
        $this->comment = $comment;
        return $this;
    }
}