<?php
declare(strict_types = 1);

namespace Rarus\Restart\Guests;

/**
 * Class Guest
 * @package Rarus\Restart\Guests
 */
interface GuestInterface
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
    public function getName(): string;

    /**
     * @return string
     */
    public function getFirstName(): string;

    /**
     * @return string
     */
    public function getLastName(): string;

    /**
     * @return string
     */
    public function getPatronymic(): string;

    /**
     * @return \DateTime | null
     */
    public function getBirthday();

    /**
     * @return string
     */
    public function getDeliveryAddress(): string;

    /**
     * @return string
     */
    public function getActualAddress(): string;

    /**
     * @return string
     */
    public function getDeliveryMetro(): string;

    /**
     * @return string
     */
    public function getDeliveryStreet(): string;

    /**
     * @return string
     */
    public function getDeliveryDistrict(): string;

    /**
     * @return string
     */
    public function getFirstPhone(): string;

    /**
     * @return string
     */
    public function getSecondPhone(): string;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return bool
     */
    public function isBlacklisted(): bool;

    /**
     * @return string
     */
    public function getBlacklistedReason(): string;

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime;
}