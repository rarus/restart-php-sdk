<?php
namespace Rarus\Restart\Menu;

/**
 * Class Menu
 * @package Rarus\Restart\Menu
 */
class Menu
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


    public function __construct()
    {

    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    protected function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    protected function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime
     */
    public function getDateBegin()
    {
        return $this->dateBegin;
    }

    /**
     * @param \DateTime $dateBegin
     */
    protected function setDateBegin(\DateTime $dateBegin)
    {
        $this->dateBegin = $dateBegin;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * @param \DateTime $dateEnd
     */
    protected function setDateEnd(\DateTime $dateEnd)
    {
        $this->dateEnd = $dateEnd;
    }

    /**
     * @return string
     */
    public function getWeek()
    {
        return $this->week;
    }

    /**
     * @param string $week
     */
    protected function setWeek($week)
    {
        $this->week = $week;
    }

    /**
     * @return string
     */
    public function getArm()
    {
        return $this->arm;
    }

    /**
     * @param string $arm
     */
    protected function setArm($arm)
    {
        $this->arm = $arm;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     */
    protected function setTimestamp(\DateTime $timestamp)
    {
        $this->timestamp = $timestamp;
    }
}
