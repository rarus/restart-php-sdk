<?php
declare(strict_types = 1);

namespace Rarus\Restart\Menu;

/**
 * Class Menu
 * @package Rarus\Restart\Menu
 */
interface MenuInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return \DateTime
     */
    public function getDateBegin();

    /**
     * @return \DateTime
     */
    public function getDateEnd();

    /**
     * @return string
     */
    public function getWeek();

    /**
     * @return string
     */
    public function getArm();

    /**
     * @return \DateTime
     */
    public function getTimestamp();
}