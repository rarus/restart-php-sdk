<?php
declare(strict_types = 1);

namespace Rarus\Restart\Guests;

use Fig\Http\Message\RequestMethodInterface as RequestMethod;

use Psr\Log\{
    NullLogger, LoggerInterface
};

use Rarus\Restart\ {
    ApiClientInterface,
    Exceptions\RestartException,
    Exceptions\ApiRestartException,
    Exceptions\ItemNotFoundRestartException
};

/**
 * Class GuestManager
 * @package Rarus\Restart\Guests
 */
class GuestManager
{
    /**
     * @var ApiClientInterface
     */
    protected $apiClient;
    /**
     * @var LoggerInterface
     */
    protected $log;

    public function __construct(ApiClientInterface $obApiClient, LoggerInterface $obLogger = null)
    {
        $this->apiClient = $obApiClient;
        if ($obLogger !== null) {
            $this->log = $obLogger;
        } else {
            $this->log = new NullLogger();
        }
        $this->log->info('rarus.restart.guests.manager.init');
    }

    /**
     * получение списка гостей по фильтру или без
     *
     * @param string $id
     * @param string $name
     * @param string $email
     * @param string $phone
     * @param string $cardId
     * @param null $cardCode
     * @param null $isGroup
     * @param null $parentId
     * @param null $timestamp
     * @return \SplObjectStorage
     * @throws ApiRestartException
     */
    public function getList(
        string $id = '',
        string $name = '',
        string $email = '',
        string $phone = '',
        $cardId = null,
        $cardCode = null,
        $timestamp = null,
        $isGroup = null,
        $parentId = null
    ): \SplObjectStorage
    {
        $this->log->info(sprintf('try to get guests list'));

        $arQuery = [];
        if ($id !== '') {
            $arQuery['id'] = $id;
        }
        if ($name !== '') {
            $arQuery['filter'] = $name;
        }
        if ($email !== '') {
            $arQuery['email'] = $email;
        }
        if ($phone !== '') {
            $arQuery['phone'] = $phone;
        }

        $obStorage = new \SplObjectStorage();

        try {
            $arResult = $this->apiClient->executeApiRequest(sprintf('/guest?%s', http_build_query($arQuery)), RequestMethod::METHOD_GET);
        } catch (ItemNotFoundRestartException $e) {
            return $obStorage;
        }

        if (!array_key_exists('guest', $arResult)) {
            $errMsg = sprintf('key [guest] not found in server response');
            $this->log->error($errMsg, ['server_response_keys' => array_keys($arResult)]);
            throw new ApiRestartException($errMsg);
        }

        foreach ($arResult['guest'] as $cnt => $arGuest) {
            $obStorage->attach(Guest::initFromServerResponse($arGuest));
        }
        $this->log->info(sprintf('return [%s] guests', $obStorage->count()));

        return $obStorage;
    }

    /**
     * @param GuestInterface $obNewGuest
     *
     * @return GuestInterface
     */
    public
    function add(GuestInterface $obNewGuest): GuestInterface
    {
        $arNewGuest = [
            'parent_id' => $obNewGuest->getParentId(),
            'isgroup' => $obNewGuest->isGroup(),
            'first_name' => $obNewGuest->getFirstName(),
            'patronymic' => $obNewGuest->getPatronymic(),
            'last_name' => $obNewGuest->getLastName(),
            'date_birth' => $obNewGuest->getBirthday()->format('Y.m.d H:i:s'),
            'delivery_address' => $obNewGuest->getDeliveryAddress(),
            'actual_address' => $obNewGuest->getActualAddress(),
            'phone1' => $obNewGuest->getFirstPhone(),
            'phone2' => $obNewGuest->getSecondPhone(),
            'email' => $obNewGuest->getEmail()
        ];
        $this->log->debug('try to add guest', $arNewGuest);

        $arResult = $this->apiClient->executeApiRequest(sprintf('/guest/add'), RequestMethod::METHOD_POST,
            [
                'json' => $arNewGuest
            ]
        );
        $this->log->debug('rarus.restart.guests.manager.add', $arResult);

        return $this->getGuestById($arResult['id']);
    }

    /**
     * @param string $guestId
     * @return GuestInterface
     */
    public function getGuestById(string $guestId): GuestInterface
    {
        $arResult = $this->apiClient->executeApiRequest(sprintf('/guest?id=%s', $guestId), RequestMethod::METHOD_GET);
        return Guest::initFromServerResponse($arResult['guest'][0]);
    }
}