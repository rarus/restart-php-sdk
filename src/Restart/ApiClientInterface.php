<?php
declare(strict_types = 1);

namespace Rarus\Restart;

use GuzzleHttp\Exception\GuzzleException;

use Rarus\Restart\{
    Exceptions\RestartException,
    Exceptions\ApiRestartException
};

/**
 * Class Client
 *
 * @package Rarus\Restart
 */
interface ApiClientInterface
{
    /**
     * @param $apiMethod
     * @param $requestType
     * @param $arHttpRequestOptions
     *
     * @throws \RuntimeException on failure.
     * @throws GuzzleException
     * @throws RestartException
     * @throws ApiRestartException
     *
     * @return array
     */
    public function executeApiRequest($apiMethod, $requestType, array $arHttpRequestOptions = array()): array;
}