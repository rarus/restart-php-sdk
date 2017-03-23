<?php
declare(strict_types = 1);

namespace Rarus\Restart\Guests;

use Rarus\Restart\Common\Address\AddressInfo;
use Rarus\Restart\Common\Users\UserInfo;

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
     * @return UserInfo
     */
    public function getUserInfo(): UserInfo;

    /**
     * @return bool
     */
    public function isGroup(): bool;

    /**
     * @return AddressInfo
     */
    public function getDeliveryAddress(): AddressInfo;

    /**
     * @return AddressInfo
     */
    public function getActualAddress(): AddressInfo;

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