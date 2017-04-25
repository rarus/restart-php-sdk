<?php
declare(strict_types = 1);

namespace Rarus\Restart\DiscountCards;

use Fig\Http\Message\RequestMethodInterface as RequestMethod;

use Psr\Log\{
    NullLogger, LoggerInterface
};

use Rarus\Restart\ {
    ApiClientInterface,
    Exceptions\RestartException,
    Exceptions\ApiRestartException
};

/**
 * Class DiscountCardManager
 * @package Rarus\Restart\DiscountCards
 */
class DiscountCardManager
{
    /**
     * @var ApiClientInterface
     */
    protected $apiClient;
    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * DiscountCardManager constructor.
     * @param ApiClientInterface $obApiClient
     * @param LoggerInterface|null $obLogger
     */
    public function __construct(ApiClientInterface $obApiClient, LoggerInterface $obLogger = null)
    {
        $this->apiClient = $obApiClient;
        if ($obLogger !== null) {
            $this->log = $obLogger;
        } else {
            $this->log = new NullLogger();
        }
        $this->log->info('rarus.restart.discountcard.manager.init');
    }

    /**
     * получение списка дисконтных карт
     * @param string $id Идентификатор карты. Необязательный
     * @param string $name Поле для фильтрации по фрагменту [name]. Необязательный
     * @param string $parentId Родитель. Необязательный
     * @param string $code Код. Необязательный
     * @param string $guestId Идентификатор гостя. Необязательный
     * @param \DateTime $timestamp Возвращает объекты, дата изменения которых больше указанного timestamp. Необязательный
     * @return \SplObjectStorage
     * @throws ApiRestartException
     */
    public function getList(string $id = '', string $name = '', string $parentId = '', string $code = '', string $guestId = '', \DateTime $timestamp = null): \SplObjectStorage
    {
        $this->log->debug('rarus.restart.discountcard.manager.card.getlist');

        $arQuery = [];
        if ($id !== '') {
            $arQuery['id'] = $id;
        }
        if ($name !== '') {
            $arQuery['name'] = $name;
        }
        if ($parentId !== '') {
            $arQuery['parent_id'] = $parentId;
        }
        if ($code !== '') {
            $arQuery['parent_id'] = $name;
        }


        $arResult = $this->apiClient->executeApiRequest(sprintf('/discountcard?%s', http_build_query($arQuery)), RequestMethod::METHOD_GET);

        if (!array_key_exists('card', $arResult)) {
            $errMsg = sprintf('key [card] not found in server response');
            $this->log->error($errMsg, ['server_response_keys' => array_keys($arResult)]);
            throw new ApiRestartException($errMsg);
        }
        $this->log->info(sprintf('return [%s] discount card item in array', count($arResult['card'])));

        $obStorage = new \SplObjectStorage();
        foreach ($arResult['card'] as $cnt => $arCard) {
            $obStorage->attach(DiscountCard::initFromServerResponse($arCard));
        }
        $this->log->info(sprintf('return [%s] discount card objects', $obStorage->count()));

        return $obStorage;
    }

    /**
     * добавление дисконтной карты
     * @param DiscountCardInterface $obNewCard
     * @return DiscountCardInterface
     */
    public function add(DiscountCardInterface $obNewCard): DiscountCardInterface
    {

        $arNewCard = [
            'parent_id' => $obNewCard->getParentId(),
            'isgroup' => $obNewCard->isGroup(),
            'code' => $obNewCard->getCode(),
            'name' => $obNewCard->getName(),
            'blocked' => $obNewCard->isBlocked(),
            'block_reason' => $obNewCard->getBlockReason(),
            'comment' => $obNewCard->getComment(),
            'guest_id' => $obNewCard->getGuestId()
        ];
        $this->log->debug('try to add new card', $arNewCard);

        $arResult = $this->apiClient->executeApiRequest(sprintf('/discountcard/add'), RequestMethod::METHOD_POST,
            [
                'json' => $arNewCard
            ]
        );

        $this->log->info('rarus.restart.discountcard.manager.card.add', ['id' => $arResult['id']]);
        return $this->getDiscountCardById($arResult['id']);
    }

    /**
     * получение дисконтной карты по её идентификатору
     * @param string $discountCardId
     * @return DiscountCardInterface
     */
    public function getDiscountCardById(string $discountCardId): DiscountCardInterface
    {
        $arResult = $this->apiClient->executeApiRequest(sprintf('/discountcard?id=%s', $discountCardId), RequestMethod::METHOD_GET);
        return DiscountCard::initFromServerResponse($arResult['card'][0]);
    }
}