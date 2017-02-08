<?php
namespace Rarus\Restart\Auth;

/**
 * Class Token
 * @package Rarus\Restart\Auth
 */
class Token implements TokenInterface
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var int
     */
    protected $expires;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var int
     */
    protected $code;

    /**
     * Token constructor.
     *
     * @param $token
     * @param $expires
     * @param $code
     * @param $message
     */
    public function __construct($token, $expires, $code, $message)
    {
        $this->setToken($token);
        $this->setExpires($expires);
        $this->setCode($code);
        $this->setMessage($message);
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    protected function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return int
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param int $expires
     */
    protected function setExpires($expires)
    {
        $this->expires = (int)$expires;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    protected function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    protected function setCode($code)
    {
        $this->code = (int)$code;
    }
}