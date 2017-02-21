<?php
declare(strict_types = 1);

namespace Rarus\Restart\DiscountCards;

/**
 * Class DiscountCard
 * @package Rarus\Restart\DiscountCards
 */
interface DiscountCardInterface
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
    public function isGroup(): bool;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return bool
     */
    public function isBlocked(): bool;

    /**
     * @return string
     */
    public function getBlockReason(): string;

    /**
     * @return string
     */
    public function getComment(): string;

    /**
     * @return string
     */
    public function getImageUrl(): string;

    /**
     * @return string
     */
    public function getGuestId(): string;

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime;
}