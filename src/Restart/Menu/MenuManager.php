<?php
declare(strict_types = 1);

namespace Rarus\Restart\Menu;

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
 * Class MenuManager
 * @package Rarus\Restart\Menu
 */
class MenuManager
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
        $this->log->info('rarus.restart.menu.manager.init');
    }

    /**
     * получение списка меню
     *
     * @throws \RuntimeException on failure.
     * @throws RestartException
     * @throws ApiRestartException
     *
     * @return \SplObjectStorage
     */
    public function getMenuList(): \SplObjectStorage
    {
        $arResult = $this->apiClient->executeApiRequest('/menu', RequestMethod::METHOD_GET);
        $obStorage = new \SplObjectStorage();

        if (!array_key_exists('menu', $arResult)) {
            $errMsg = sprintf('key [menu] not found in server response');
            $this->log->error($errMsg, ['server_response_keys' => array_keys($arResult)]);
            throw new ApiRestartException($errMsg);
        }

        foreach ($arResult['menu'] as $cnt => $arMenu) {
            $obStorage->attach(Menu::initFromServerResponse($arMenu));
        }
        $this->log->info(sprintf('return [%s] menu', $obStorage->count()));

        return $obStorage;
    }

    /**
     * получение пунктов меню для конкретного меню
     *
     * @param MenuInterface $menu
     *
     * @throws RestartException
     * @throws ApiRestartException
     *
     * @return \SplObjectStorage
     */
    public function getMenuItemList(MenuInterface $menu): \SplObjectStorage
    {
        $this->log->info(sprintf('try to get menu items for menu [%s] with id [%s]', $menu->getName(),
            $menu->getId()));

        $arResult = $this->apiClient->executeApiRequest(sprintf('/menu/%s/items', $menu->getId()),
            RequestMethod::METHOD_GET);

        if (!array_key_exists('menu_items', $arResult)) {
            $errMsg = sprintf('key [menu_items] not found in server response');
            $this->log->error($errMsg, ['server_response_keys' => array_keys($arResult)]);
            throw new ApiRestartException($errMsg);
        }

        $obStorage = new \SplObjectStorage();
        foreach ($arResult['menu_items'] as $cnt => $arMenuItem) {
            $obStorage->attach(MenuItem::initFromServerResponse($arMenuItem));
        }
        $this->log->info(sprintf('return [%s] menu items', $obStorage->count()));

        return $obStorage;
    }
}