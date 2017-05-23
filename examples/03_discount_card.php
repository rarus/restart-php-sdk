<?php

require_once __DIR__ . '/init.php';

use \Rarus\Restart\{
    ApiClient,
    DiscountCards\DiscountCard,
    DiscountCards\DiscountCardManager,
    DiscountCards\DiscountCardInterface
};


$discountCardManager = new DiscountCardManager($apiClient, $log);

// получение списка дисконтных карт
$obStorage = $discountCardManager->getList('', 'юдин', '', '', '');

print('список дисконтных карт');
foreach ($obStorage as $cardItem) {
    /**
     * @var $cardItem DiscountCardInterface
     */
    print(sprintf('id: %s' . PHP_EOL, $cardItem->getId()));
    print(sprintf('parent id: %s' . PHP_EOL, $cardItem->getParentId()));
    print(sprintf('is group: %s' . PHP_EOL, $cardItem->isGroup() ? 'true' : 'false'));
    print(sprintf('code: %s' . PHP_EOL, $cardItem->getCode()));
    print(sprintf('name: %s' . PHP_EOL, $cardItem->getName()));
    print(sprintf('is blocked: %s' . PHP_EOL, $cardItem->isBlocked() ? 'true' : 'false'));
    print(sprintf('block reason: %s' . PHP_EOL, $cardItem->getBlockReason()));
    print(sprintf('comment: %s' . PHP_EOL, $cardItem->getComment()));
    print(sprintf('image url: %s' . PHP_EOL, $cardItem->getImageUrl()));
    print(sprintf('guest id: %s' . PHP_EOL, $cardItem->getGuestId()));
    print(sprintf('timestamp: %s' . PHP_EOL, $cardItem->getTimestamp()->format(\DateTime::ATOM)));
    print('-----------' . PHP_EOL);
}