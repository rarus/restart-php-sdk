<?php
declare(strict_types = 1);

namespace Rarus\Restart\Common\Users;

/**
 * Class UserInfo
 * @package Rarus\Restart\Users
 */
class UserInfo
{
    /**
     * @var string Наименование, представление на формах и в отчетах
     */
    protected $name;
    /**
     * @var string Имя
     */
    protected $firstName;
    /**
     * @var string Фамилия
     */
    protected $lastName;
    /**
     * @var string Отчество
     */
    protected $patronymic;
    /**
     * @var \DateTime Дата рождения
     */
    protected $birthday;
    /**
     * @var string Контактный телефон
     */
    protected $firstPhone;
    /**
     * @var string Дополнительный телефон
     */
    protected $secondPhone;
    /**
     * @var string Адрес e-mail
     */
    protected $email;

    /**
     * @param array $arUserInfo
     * @param string $serverTimeFormat
     *
     * @return UserInfo
     */
    public static function initFromServerResponse(array $arUserInfo, $serverTimeFormat = 'Y.m.d H:i:s.u'): UserInfo
    {

        $obUserInfo = new UserInfo();

        if ($arUserInfo['date_birth'] !== '') {
            $obUserInfo->setBirthday(\DateTime::createFromFormat($serverTimeFormat, $arUserInfo['date_birth']));
        }

        $obUserInfo
            ->setName($arUserInfo['name'])
            ->setFirstName($arUserInfo['first_name'])
            ->setLastName($arUserInfo['last_name'])
            ->setPatronymic($arUserInfo['patronymic'])
            ->setFirstPhone($arUserInfo['phone1'])
            ->setSecondPhone($arUserInfo['phone2'])
            ->setEmail($arUserInfo['email']);
        if ($arUserInfo['date_birth'] !== '') {
            $obUserInfo->setBirthday(\DateTime::createFromFormat($serverTimeFormat, $arUserInfo['date_birth']));
        }
        return $obUserInfo;
    }

    /**
     * @param string $name
     * @param string $firstName
     * @param string $lastName
     * @param string $patronymic
     * @param null $birthday
     * @param string $email
     * @param string $firstPhone
     * @param string $secondPhone
     * @return UserInfo
     */
    public static function createNewUserInfoItem(
        string $name = '',
        string $firstName = '',
        string $lastName = '',
        string $patronymic = '',
        $birthday = null,
        string $email = '',
        string $firstPhone = '',
        string $secondPhone = ''
    ): UserInfo
    {
        $obUserInfo = new UserInfo();

        if ($birthday instanceof \DateTime) {
            $obUserInfo->setBirthday($birthday);
        }

        $obUserInfo
            ->setName($name)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPatronymic($patronymic)
            ->setFirstPhone($firstPhone)
            ->setSecondPhone($secondPhone)
            ->setEmail($email);

        return $obUserInfo;
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
     * @return UserInfo
     */
    protected function setName(string $name): UserInfo
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return UserInfo
     */
    protected function setFirstName(string $firstName): UserInfo
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return UserInfo
     */
    protected function setLastName(string $lastName): UserInfo
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    /**
     * @param string $patronymic
     *
     * @return UserInfo
     */
    protected function setPatronymic(string $patronymic): UserInfo
    {
        $this->patronymic = $patronymic;
        return $this;
    }

    /**
     * @return \DateTime | null
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     *
     * @return UserInfo
     */
    protected function setBirthday(\DateTime $birthday): UserInfo
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstPhone(): string
    {
        return $this->firstPhone;
    }

    /**
     * @param string $firstPhone
     *
     * @return UserInfo
     */
    protected function setFirstPhone(string $firstPhone): UserInfo
    {
        $this->firstPhone = $firstPhone;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecondPhone(): string
    {
        return $this->secondPhone;
    }

    /**
     * @param string $secondPhone
     *
     * @return UserInfo
     */
    protected function setSecondPhone(string $secondPhone): UserInfo
    {
        $this->secondPhone = $secondPhone;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return UserInfo
     */
    protected function setEmail(string $email): UserInfo
    {
        $this->email = $email;
        return $this;
    }
}