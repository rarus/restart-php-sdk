<?php
namespace Rarus\Restart;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;

use Rarus\Restart\Auth\Token;
use Rarus\Restart\Auth\TokenInterface;
use Rarus\Restart\Exceptions\ApiRestartException;
use Rarus\Restart\Exceptions\RestartException;

/**
 * Class ApiClient
 *
 * @package Rarus\Restart
 */
class ApiClient implements ApiClientInterface
{
    /**
     * @var string SDK version
     */
    const SDK_VERSION = '1.0.0';

    /**
     * @var string user agent
     */
    const API_USER_AGENT = 'rarus-bonus-server-php-sdk';

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * @var HandlerStack
     */
    protected $guzzleHandlerStack;

    /**
     * @var string
     */
    protected $apiEndpoint;

    /**
     * @var TokenInterface
     */
    protected $authToken;

    /**
     * @var float
     */
    protected $connectTimeout;

    /**
     * Client constructor.
     *
     * @param string $apiEndpointUrl
     * @param ClientInterface $obHttpClient
     * @param LoggerInterface|null $obLogger
     */
    public function __construct($apiEndpointUrl, ClientInterface $obHttpClient, LoggerInterface $obLogger = null)
    {
        $this->apiEndpoint = $apiEndpointUrl;
        $this->httpClient = $obHttpClient;
        $this->authToken = null;

        if ($obLogger !== null) {
            $this->log = $obLogger;
        } else {
            $this->log = new NullLogger();
        }
        $this->guzzleHandlerStack = HandlerStack::create();
        $this->setConnectTimeout(2);

        $this->log->debug('init bonus server api wrapper complete');
    }

    /**
     * @param $connectTimeout
     */
    public function setConnectTimeout($connectTimeout)
    {
        $this->connectTimeout = (float)$connectTimeout;
    }

    /**
     * @return float
     */
    protected function getConnectTimeout()
    {
        return $this->connectTimeout;
    }

    /**
     * @param HandlerStack $guzzleHandlerStack
     */
    public function setGuzzleHandlerStack(HandlerStack $guzzleHandlerStack)
    {
        $this->guzzleHandlerStack = $guzzleHandlerStack;
    }

    /**
     * @return HandlerStack
     */
    protected function getGuzzleHandlerStack()
    {
        return $this->guzzleHandlerStack;
    }

    /**
     * получение авторизационного токена для роли организация
     *
     * @param $password
     * @param null $sessionId
     *
     * @throws \RuntimeException on failure.
     * @throws GuzzleException
     * @throws RestartException
     * @throws ApiRestartException
     *
     * @return TokenInterface
     */
    public function getAuthToken($password, $sessionId = null)
    {
        $arResult = $this->executeApiRequest('/sign_in', 'POST', [
            'body' => json_encode([
                'password' => sha1($password),
                'session_id' => $sessionId
            ])
        ]);
        return new Token($arResult['token'], $arResult['expires'], $arResult['code'], $arResult['message']);
    }

    /**
     * @param TokenInterface $authToken
     */
    public function setAuthToken(TokenInterface $authToken)
    {
        $this->authToken = $authToken;
    }

    /**
     * get default HttpRequest options
     *
     * @return array
     */
    protected function getDefaultHttpRequestOptions()
    {
        return [
            'handler' => $this->getGuzzleHandlerStack(),
            'connect_timeout' => $this->getConnectTimeout(),
            'headers' => [
                'X-ENVIRONMENT-PHP-VERSION: ' . phpversion(),
                'X-ENVIRONMENT-SDK-VERSION: ' . strtolower(self::API_USER_AGENT . '-v' . self::SDK_VERSION)
            ]
        ];
    }

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
     * @return null|string
     */
    public function executeApiRequest($apiMethod, $requestType, array $arHttpRequestOptions = array())
    {
        $arResult = null;

        $defaultHttpRequestOptions = array_merge($arHttpRequestOptions, $this->getDefaultHttpRequestOptions());
        // add auth headers
        $defaultHttpRequestOptions['headers']['token'] = (null === $this->authToken) ? '' : $this->authToken->getToken();

        try {
            $this->log->debug(sprintf('try to send api request [%s]', $apiMethod), [$defaultHttpRequestOptions]);

            $obResponse = $this->httpClient->request(
                $requestType,
                $this->apiEndpoint . $apiMethod,
                $defaultHttpRequestOptions
            );

            $this->log->debug(sprintf('request http status [%s] and reason phrase [%s]',
                    $obResponse->getStatusCode(), $obResponse->getReasonPhrase())
            );

            $obResponseBody = $obResponse->getBody();
            $obResponseBody->rewind();
            $arResult = $this->decodeApiJsonResponse($obResponseBody->getContents());
        } catch (ClientException $e) {
            $this->log->error(sprintf('http client error [%s]', $e->getMessage()));
            $this->handleBonusServerApiErrors($e);
        }
        return $arResult;
    }

    /**
     * @param ClientException $clientException
     *
     * @throws ApiRestartException
     * @throws RestartException
     * @return void
     */
    protected function handleBonusServerApiErrors(ClientException $clientException)
    {
        $obErrorResponse = $clientException->getResponse();
        $obStream = $obErrorResponse->getBody();
        $arServerResponse = $this->decodeApiJsonResponse($obStream->getContents());

        $errorMessage = sprintf('bonus server error: code [%s], message [%s], ',
            $arServerResponse['code'], $arServerResponse['message']);

        $this->log->error($errorMessage);

        throw new ApiRestartException($errorMessage);
    }

    /**
     * @param $jsonApiResponse
     *
     * @return mixed
     * @throws RestartException
     */
    protected function decodeApiJsonResponse($jsonApiResponse)
    {
        // handling server-side API errors: empty response
        if ($jsonApiResponse === '') {
            $errorMsg = sprintf('empty response from server');
            $this->log->error($errorMsg);
            throw new RestartException($errorMsg);
        }
        // handling json_decode errors
        $jsonResult = json_decode($jsonApiResponse, true);
        $jsonErrorCode = json_last_error();
        if (null === $jsonResult && (JSON_ERROR_NONE !== $jsonErrorCode)) {
            /**
             * @todo add function json_last_error_msg()
             */
            $errorMsg = 'fatal error in function json_decode.' . PHP_EOL . 'Error code: ' . $jsonErrorCode . PHP_EOL;
            $this->log->error($errorMsg);
            throw new RestartException($errorMsg);
        }
        return $jsonResult;
    }
}