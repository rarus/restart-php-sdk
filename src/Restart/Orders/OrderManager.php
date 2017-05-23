<?php
declare(strict_types=1);

namespace Rarus\Restart\Orders;

use Fig\Http\Message\RequestMethodInterface as RequestMethod;

use Psr\Log\{
    NullLogger, LoggerInterface
};

use Rarus\Restart\ {
    ApiClientInterface,
    Exceptions\ApiRestartException
};

/**
 * Class OrderManager
 * @package Rarus\Restart\Orders
 */
class OrderManager
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
     * OrderManager constructor.
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
        $this->log->info('rarus.restart.order.manager.init');
    }

    /**
     * получение списка заказов
     *
     * @param OrderQueryFilter $orderQueryFilter
     * @param bool $isLoadOrderItems
     * @return \SplObjectStorage
     * @throws ApiRestartException
     */
    public function getList(OrderQueryFilter $orderQueryFilter, bool $isLoadOrderItems = false): \SplObjectStorage
    {
        $this->log->info('rarus.restart.order.manager.getList', [
            'ORDER_FILTER' => $orderQueryFilter->getQueryFilter(),
            'IS_LOAD_ORDER_ITEMS' => $isLoadOrderItems
        ]);

        $arSelect = [
            'items' => $isLoadOrderItems === true ? 'true' : 'false'
        ];
        $obStorage = new \SplObjectStorage();

        try {
            $arResult = $this->apiClient->executeApiRequest(sprintf('/order?%s&%s', $orderQueryFilter->getQueryFilter(), http_build_query($arSelect)), RequestMethod::METHOD_GET);

            if (!array_key_exists('order', $arResult)) {
                $errMsg = sprintf('key [order] not found in server response');
                $this->log->error($errMsg, ['server_response' => $arResult]);
                throw new ApiRestartException($errMsg);
            }

            foreach ((array)$arResult['order'] as $orderItem) {
                $obStorage->attach(Order::initFromServerResponse($orderItem));
            }

            $this->log->info('rarus.restart.order.manager.getList.result', [
                'ORDERS_COUNT' => $obStorage->count()
            ]);

        } catch (\Exception $e) {
            throw new ApiRestartException($e->getMessage());
        }
        return $obStorage;
    }

    /**
     * добавление нового заказа
     *
     * @param Order $obNewOrder
     * @return string
     */
    public function add(Order $obNewOrder): string
    {
        $this->log->debug('rarus.restart.order.manager.add', [
            'ORDER_DATA' => $obNewOrder->getDataAsArray()
        ]);

        $arResult = $this->apiClient->executeApiRequest('/order/add', RequestMethod::METHOD_POST,
            [
                'json' => $obNewOrder->getDataAsArray()
            ]
        );
        $this->log->debug('rarus.restart.order.manager.add.result', [$arResult]);
        return $arResult['id'];
    }

    /**
     * получение информации о заказе по его ID
     *
     * @param string $orderId
     * @param bool $isLoadOrderItems
     * @return Order
     */
    public function getById(string $orderId, bool $isLoadOrderItems = false): Order
    {
        $this->log->debug('rarus.restart.order.manager.getById', [
            'ORDER_ID' => $orderId,
            'IS_LOAD_ORDER_ITEMS' => $isLoadOrderItems
        ]);

        $arQuery = [
            'id' => $orderId,
            'items' => $isLoadOrderItems === true ? 'true' : 'false'
        ];
        $arResult = $this->apiClient->executeApiRequest(sprintf('/order?%s', http_build_query($arQuery)), RequestMethod::METHOD_GET);
        $this->log->debug('rarus.restart.order.manager.getById.result', [$arResult]);
        return Order::initFromServerResponse($arResult['order'][0]);
    }
}