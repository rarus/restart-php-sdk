<?php
namespace Rarus\Restart;

use GuzzleHttp\Exception\GuzzleException;
use Rarus\Restart\Exceptions\RestartException;


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
     *
     * @return null|string
     */
    public function executeApiRequest($apiMethod, $requestType, array $arHttpRequestOptions = array());
}