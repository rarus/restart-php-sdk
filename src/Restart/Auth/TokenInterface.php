<?php

namespace Rarus\Restart\Auth;

/**
 * Interface TokenInterface
 * @package Rarus\Restart\Auth
 */
interface TokenInterface
{
    /**
     * @return string
     */
    public function getToken();

    /**
     * @return int
     */
    public function getExpires();

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @return int
     */
    public function getCode();
}