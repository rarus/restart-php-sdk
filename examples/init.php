<?php

require_once '..' . '/vendor/autoload.php';

use \Monolog\Logger;

use \GuzzleHttp\{
    HandlerStack,
    Middleware,
    MessageFormatter
};

use \Rarus\Restart\{
    ApiClient
};

// инициализируем логи
$log = new Logger("restart-php-sdk-log");
$log->pushHandler(new \Monolog\Handler\StreamHandler('restart-php-sdk.log', Logger::DEBUG));

$guzzleHandlerStack = HandlerStack::create();
$guzzleHandlerStack->push(
    Middleware::log(
        $log,
        new MessageFormatter(MessageFormatter::SHORT)
    )
);
// http-клиент
$httpClient = new \GuzzleHttp\Client();
$log->info('=================================================================');
$log->info('restart api test START');

// параметры подключения ожидаем как аргументы командной строки
$argv = getopt('', array('url::', 'password::'));
$example = 'php -f 01_menu.php -- --url=https://127.0.0.1 --password=123456';
if ($argv['url'] === null) {
    $errMsg = sprintf('ошибка: не найден url для подключения, пример вызова скрипта: %s', $example);
    $log->error($errMsg, [$argv]);
    print($errMsg . PHP_EOL);
    exit();
}
if ($argv['password'] === null) {
    $errMsg = sprintf('ошибка: не найден пароль для подключения, пример вызова скрипта: %s', $example);
    $log->error($errMsg, [$argv]);
    print($errMsg . PHP_EOL);
    exit();
}

// http-клиент
$httpClient = new \GuzzleHttp\Client();
$apiClient = new ApiClient($argv['url'], $httpClient, $log);
$apiClient->setGuzzleHandlerStack($guzzleHandlerStack);

/**
 * @var \Rarus\Restart\Auth\TokenInterface $authToken
 */
$authToken = $apiClient->getAuthToken($argv['password']);
$apiClient->setAuthToken($authToken);