<?php
declare(strict_types = 1);

namespace Rarus\Restart\Menu;

/**
 * Class Menu
 * @package Rarus\Restart\Menu
 */
class Menu implements MenuInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \DateTime
     */
    protected $dateBegin;

    /**
     * @var \DateTime
     */
    protected $dateEnd;

    /**
     * @var string
     */
    protected $week;

    /**
     * @var string
     */
    protected $arm;

    /**
     * @var \DateTime
     */
    protected $timestamp;

    /**
     * @param array $arMenu
     * @param string $serverTimestampFormat
     *
     * @return MenuInterface
     */
    public static function initFromServerResponse(array $arMenu, $serverTimestampFormat = 'Y.m.d H:i:s.u'): MenuInterface
    {
        $obMenuItem = new Menu();
        $obMenuItem
            ->setId($arMenu['id'])
            ->setName($arMenu['name'])
            ->setDateBegin(new \DateTime($arMenu['date_begin']))
            ->setDateEnd(new \DateTime($arMenu['date_end']))
            ->setWeek($arMenu['week'])
            ->setTimestamp(\DateTime::createFromFormat($serverTimestampFormat, $arMenu['timestamp']))
            ->setArm($arMenu['arm']);

        return $obMenuItem;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    protected function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    protected function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateBegin(): \DateTime
    {
        return $this->dateBegin;
    }

    /**
     * @param \DateTime $dateBegin
     *
     * @return $this
     */
    protected function setDateBegin(\DateTime $dateBegin)
    {
        $this->dateBegin = $dateBegin;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnd(): \DateTime
    {
        return $this->dateEnd;
    }


    protected function setDateEnd(\DateTime $dateEnd)
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    /**
     * @return string
     */
    public function getWeek(): string
    {
        return $this->week;
    }

    /**
     * @param $week
     *
     * @return $this
     */
    protected function setWeek($week)
    {
        $this->week = $week;
        return $this;
    }

    /**
     * @return string
     */
    public function getArm(): string
    {
        return $this->arm;
    }

    /**
     * @param $arm
     *
     * @return $this
     */
    protected function setArm($arm)
    {
        $this->arm = $arm;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     *
     * @return $this
     */
    protected function setTimestamp(\DateTime $timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}