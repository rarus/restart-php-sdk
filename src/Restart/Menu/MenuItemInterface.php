<?php
declare(strict_types = 1);

namespace Rarus\Restart\Menu;

/**
 * Interface MenuItemInterface
 * @package Rarus\Restart\Menu
 */
interface MenuItemInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getParentId(): string;

    /**
     * @return bool
     */
    public function isStopped(): bool;

    /**
     * @return bool
     */
    public function isGroup(): bool;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return int
     */
    public function getPosition(): int;

    /**
     * @return string
     */
    public function getImage(): string;

    /**
     * @return double
     */
    public function getPrice(): float;

    /**
     * @return string
     */
    public function getComment(): string;
}