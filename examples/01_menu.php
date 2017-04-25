<?php

require_once __DIR__ . '/init.php';

use \Rarus\Restart\{
    ApiClient,
    Menu\MenuInterface,
    Menu\MenuItemInterface,
    Menu\MenuManager,
    Menu\Menu
};

$obMenuManager = new MenuManager($apiClient, $log);


//пример с типами меню
$obStorage = $obMenuManager->getMenuList();
print('типы меню' . PHP_EOL);
foreach ($obStorage as $obMenu) {
    /**
     * @var MenuInterface $obMenu
     */
    print(sprintf('id: %s' . PHP_EOL, $obMenu->getId()));
    print(sprintf('name: %s' . PHP_EOL, $obMenu->getName()));
    print(sprintf('date begin: %s' . PHP_EOL, $obMenu->getDateBegin()->format(\DateTime::ATOM)));
    print(sprintf('date end: %s' . PHP_EOL, $obMenu->getDateEnd()->format(\DateTime::ATOM)));
    print(sprintf('time end: %s' . PHP_EOL, $obMenu->getDateEnd()->format('H:i:s')));
    print(sprintf('week mask: %s' . PHP_EOL, $obMenu->getWeek()));
    print(sprintf('arm: %s' . PHP_EOL, $obMenu->getArm()));
    print(sprintf('timestamp: %s' . PHP_EOL, $obMenu->getTimestamp()->format(\DateTime::ATOM)));
    print('==============' . PHP_EOL);
}

// пример с пунктами меню
$arMenu = [
    'id' => '462E5CD4-4C62-4141-8EB5-738C55F21680',
    'name' => 'основное меню',
    'date_begin' => '',
    'time_begin' => '',
    'date_end' => '',
    'time_end' => '',
    'week'=> '1111111',
    'arm'=>'all',
    'timestamp' => '2017.01.25 12:23:06.247'

];
$obMenu = Menu::initFromServerResponse($arMenu);
/**
 * @var $apiClient ApiClient
 */
$obStorage = $obMenuManager->getMenuItemList($obMenu);
print('элементы меню' . PHP_EOL);
foreach ($obStorage as $menuItem) {
    /**
     * @var MenuItemInterface $menuItem
     */
    print(sprintf('name: %s' . PHP_EOL, $menuItem->getName()));
    print(sprintf('id: %s' . PHP_EOL, $menuItem->getId()));
    print(sprintf('parent_id: %s' . PHP_EOL, $menuItem->getParentId()));
    print(sprintf('is stopped: %s' . PHP_EOL, $menuItem->isStopped() ? 'true' : 'false'));
    print(sprintf('is stopped: %s' . PHP_EOL, $menuItem->isGroup() ? 'true' : 'false'));
    print(sprintf('position: %s' . PHP_EOL, $menuItem->getPosition()));
    print(sprintf('image url: %s' . PHP_EOL, $menuItem->getImage()));
    print(sprintf('price: %s' . PHP_EOL, $menuItem->getPrice()));
    print(sprintf('comment: %s' . PHP_EOL, $menuItem->getComment()));
    print('--------' . PHP_EOL);
}
