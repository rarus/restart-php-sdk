<?php
namespace Rarus\Restart\Exceptions;
/**
 * Class RestartException
 *
 * \Exception
 *      RestartException — base class
 *          IoRestartException — I/O network errors
 *          ApiRestartException — API-level exceptions
 *
 * @package Rarus\Restart
 */
class RestartException extends \Exception
{
}